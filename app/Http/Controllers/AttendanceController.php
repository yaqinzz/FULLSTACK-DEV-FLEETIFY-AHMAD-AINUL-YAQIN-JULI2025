<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceHistory;
use App\Models\Employee;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display attendance page
     */
    public function index(Request $request)
    {
        $employees = Employee::with('department')->get();
        $departments = Department::all();

        // Get latest attendance record for each employee
        $latestAttendanceIds = Attendance::select('employee_id')
            ->selectRaw('MAX(id) as latest_id')
            ->whereNotNull('clock_in')
            ->groupBy('employee_id')
            ->pluck('latest_id');

        $query = Attendance::with(['employee.department'])
            ->whereIn('id', $latestAttendanceIds);

        // Apply filters if provided
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->department_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->orderBy('created_at', 'desc')->get();

        // Add attendance status calculations
        $attendances->map(function ($attendance) {
            $department = $attendance->employee->department;

            // Initialize status properties
            $attendance->clock_in_status = null;
            $attendance->clock_out_status = null;
            $attendance->late_minutes = 0;
            $attendance->early_minutes = 0;

            // Check clock in status
            if ($attendance->clock_in) {
                try {
                    $clockInTime = Carbon::parse($attendance->clock_in);

                    // Get max clock in time from department, default to 09:00:00 if not set
                    $maxClockInStr = $department->max_clock_in_time ?? '09:00:00';

                    // Ensure the time format is correct and remove any date parts
                    if (strlen($maxClockInStr) > 8) {
                        // If it contains date part, extract only time
                        $timePart = Carbon::parse($maxClockInStr)->format('H:i:s');
                        $maxClockInStr = $timePart;
                    } elseif (strlen($maxClockInStr) === 5) {
                        $maxClockInStr .= ':00'; // Add seconds if missing (e.g., 09:00 -> 09:00:00)
                    }

                    // Create the max clock in time for comparison (same date as clock in)
                    $todayDate = $clockInTime->format('Y-m-d');
                    $maxClockIn = Carbon::parse($todayDate . ' ' . $maxClockInStr);

                    // Compare full datetime objects
                    if ($clockInTime->lte($maxClockIn)) {
                        $attendance->clock_in_status = 'On Time';
                        $attendance->late_minutes = 0;
                    } else {
                        $attendance->clock_in_status = 'Late';
                        $attendance->late_minutes = $clockInTime->diffInMinutes($maxClockIn);
                    }
                } catch (\Exception $e) {
                    // Log the error for debugging
                    Log::error('Clock in time parsing error: ' . $e->getMessage() . ' | Max clock in: ' . ($department->max_clock_in_time ?? 'null'));
                    $attendance->clock_in_status = 'Error';
                    $attendance->late_minutes = 0;
                }
            }

            // Check clock out status
            if ($attendance->clock_out) {
                try {
                    $clockOutTime = Carbon::parse($attendance->clock_out);

                    // Get max clock out time from department, default to 17:00:00 if not set
                    $maxClockOutStr = $department->max_clock_out_time ?? '17:00:00';

                    // Ensure the time format is correct and remove any date parts
                    if (strlen($maxClockOutStr) > 8) {
                        // If it contains date part, extract only time
                        $timePart = Carbon::parse($maxClockOutStr)->format('H:i:s');
                        $maxClockOutStr = $timePart;
                    } elseif (strlen($maxClockOutStr) === 5) {
                        $maxClockOutStr .= ':00'; // Add seconds if missing (e.g., 17:00 -> 17:00:00)
                    }

                    // Create the max clock out time for comparison (same date as clock out)
                    $todayDate = $clockOutTime->format('Y-m-d');
                    $maxClockOut = Carbon::parse($todayDate . ' ' . $maxClockOutStr);

                    // Compare full datetime objects
                    if ($clockOutTime->gte($maxClockOut)) {
                        $attendance->clock_out_status = 'On Time';
                        $attendance->early_minutes = 0;
                    } else {
                        $attendance->clock_out_status = 'Early';
                        $attendance->early_minutes = $maxClockOut->diffInMinutes($clockOutTime);
                    }
                } catch (\Exception $e) {
                    // Log the error for debugging
                    Log::error('Clock out time parsing error: ' . $e->getMessage() . ' | Max clock out: ' . ($department->max_clock_out_time ?? 'null'));
                    $attendance->clock_out_status = 'Error';
                    $attendance->early_minutes = 0;
                }
            }

            // Determine overall status
            if ($attendance->clock_out) {
                // Both clock in and out exist
                if ($attendance->clock_in_status === 'Late' || $attendance->clock_out_status === 'Early') {
                    $attendance->overall_status = 'Late/Early';
                } else {
                    $attendance->overall_status = 'Complete';
                }
            } else {
                // Only clock in exists
                $attendance->overall_status = $attendance->clock_in_status ?? 'Clocked In';
            }

            // Calculate working hours
            if ($attendance->clock_in && $attendance->clock_out) {
                try {
                    $clockIn = Carbon::parse($attendance->clock_in);
                    $clockOut = Carbon::parse($attendance->clock_out);
                    $totalMinutes = $clockIn->diffInMinutes($clockOut);
                    $attendance->working_hours = intval($totalMinutes / 60);
                    $attendance->working_minutes = $totalMinutes % 60;
                } catch (\Exception $e) {
                    $attendance->working_hours = 0;
                    $attendance->working_minutes = 0;
                }
            } else {
                $attendance->working_hours = null;
                $attendance->working_minutes = null;
            }

            return $attendance;
        });

        return view('attendance.index', compact('employees', 'departments', 'attendances'));
    }

    /**
     * Clock In
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id'
        ]);

        $employee = Employee::with('department')->find($request->employee_id);
        $today = Carbon::today();

        // Check if already clocked in today
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('created_at', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Already clocked in today');
        }

        $clockInTime = Carbon::now();
        $attendanceId = 'ATT-' . $request->employee_id . '-' . $clockInTime->format('Ymd-His');

        // Create attendance record
        $attendance = Attendance::create([
            'employee_id' => $request->employee_id,
            'attendance_id' => $attendanceId,
            'clock_in' => $clockInTime
        ]);

        // Create attendance history
        AttendanceHistory::create([
            'employee_id' => $request->employee_id,
            'attendance_id' => $attendanceId,
            'date_attendance' => $clockInTime,
            'attendance_type' => 1, // 1 = In
            'description' => 'Clock In'
        ]);

        // Check if late
        $isLate = false;
        $lateMinutes = 0;

        try {
            $maxClockInStr = $employee->department->max_clock_in_time ?? '09:00:00';

            // Ensure the time format is correct and remove any date parts
            if (strlen($maxClockInStr) > 8) {
                // If it contains date part, extract only time
                $timePart = Carbon::parse($maxClockInStr)->format('H:i:s');
                $maxClockInStr = $timePart;
            } elseif (strlen($maxClockInStr) === 5) {
                $maxClockInStr .= ':00'; // Add seconds if missing
            }

            // Create the max clock in time for today
            $maxClockIn = Carbon::parse($clockInTime->format('Y-m-d') . ' ' . $maxClockInStr);

            if ($clockInTime->gt($maxClockIn)) {
                $isLate = true;
                $lateMinutes = $clockInTime->diffInMinutes($maxClockIn);
            }
        } catch (\Exception $e) {
            Log::error('Clock in time validation error: ' . $e->getMessage() . ' | Max clock in: ' . ($employee->department->max_clock_in_time ?? 'null'));
        }

        $message = $isLate ?
            "Clock in successful, but you are late by {$lateMinutes} minutes!" :
            'Clock in successful!';
        $alertType = $isLate ? 'warning' : 'success';

        return redirect()->back()->with($alertType, $message);
    }

    /**
     * Clock Out
     */
    public function clockOut(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id'
        ]);

        $employee = Employee::with('department')->find($request->employee_id);
        $today = Carbon::today();

        // Find today's attendance that hasn't been clocked out
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->whereDate('created_at', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'No active clock in found for today');
        }

        $clockOutTime = Carbon::now();

        // Update attendance record
        $attendance->update([
            'clock_out' => $clockOutTime
        ]);

        // Create attendance history
        AttendanceHistory::create([
            'employee_id' => $request->employee_id,
            'attendance_id' => $attendance->attendance_id,
            'date_attendance' => $clockOutTime,
            'attendance_type' => 2, // 2 = Out
            'description' => 'Clock Out'
        ]);

        // Check if early checkout
        $isEarly = false;
        $earlyMinutes = 0;

        try {
            $maxClockOutStr = $employee->department->max_clock_out_time ?? '17:00:00';

            // Ensure the time format is correct and remove any date parts
            if (strlen($maxClockOutStr) > 8) {
                // If it contains date part, extract only time
                $timePart = Carbon::parse($maxClockOutStr)->format('H:i:s');
                $maxClockOutStr = $timePart;
            } elseif (strlen($maxClockOutStr) === 5) {
                $maxClockOutStr .= ':00'; // Add seconds if missing
            }

            // Create the max clock out time for today
            $maxClockOut = Carbon::parse($clockOutTime->format('Y-m-d') . ' ' . $maxClockOutStr);

            if ($clockOutTime->lt($maxClockOut)) {
                $isEarly = true;
                $earlyMinutes = $maxClockOut->diffInMinutes($clockOutTime);
            }
        } catch (\Exception $e) {
            Log::error('Clock out time validation error: ' . $e->getMessage() . ' | Max clock out: ' . ($employee->department->max_clock_out_time ?? 'null'));
        }

        $message = $isEarly ?
            "Clock out successful, but you are leaving early by {$earlyMinutes} minutes!" :
            'Clock out successful!';
        $alertType = $isEarly ? 'warning' : 'success';

        return redirect()->back()->with($alertType, $message);
    }
}
