<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDepartments = Department::count();
        $totalEmployees = Employee::count();

        $today = Carbon::today();
        $todayAttendances = Attendance::whereDate('created_at', $today)->get();

        $todayCheckins = $todayAttendances->where('clock_in', '!=', null)->count();
        $todayCheckouts = $todayAttendances->where('clock_out', '!=', null)->count();

        // Recent attendance for today
        $recentAttendances = Attendance::with(['employee.department'])
            ->whereDate('created_at', $today)
            ->whereNotNull('clock_in')
            ->orderBy('clock_in', 'desc')
            ->limit(5)
            ->get();

        // Add status to recent attendances
        $recentAttendances->map(function ($attendance) {
            $department = $attendance->employee->department;

            if ($attendance->clock_in) {
                try {
                    $clockInTime = Carbon::parse($attendance->clock_in);

                    // Get max clock in time from department, default to 09:00:00 if not set
                    $maxClockInStr = $department->max_clock_in_time ?? '09:00:00';

                    // Ensure the time format is correct
                    if (strlen($maxClockInStr) === 5) {
                        $maxClockInStr .= ':00'; // Add seconds if missing (e.g., 09:00 -> 09:00:00)
                    }

                    // Create the max clock in time for comparison (same date as clock in)
                    $maxClockIn = Carbon::createFromFormat('Y-m-d H:i:s', $clockInTime->format('Y-m-d') . ' ' . $maxClockInStr);

                    // Compare full datetime objects
                    $attendance->clock_in_status = $clockInTime->lte($maxClockIn) ? 'On Time' : 'Late';
                } catch (\Exception $e) {
                    // If there's any error, just mark as clocked in without status
                    $attendance->clock_in_status = 'Clocked In';
                }
            }

            return $attendance;
        });
        return view('dashboard', compact(
            'totalDepartments',
            'totalEmployees',
            'todayCheckins',
            'todayCheckouts',
            'recentAttendances'
        ));
    }
}
