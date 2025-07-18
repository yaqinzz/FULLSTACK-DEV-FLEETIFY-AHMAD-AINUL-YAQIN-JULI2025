@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50">
    <div class="space-y-8 p-6">
        <!-- Header dengan gradient background -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Attendance Management</h1>
                    <p class="text-purple-100 text-lg">Clock in/out and view attendance logs</p>
                </div>
                <div class="text-right bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-purple-100 text-sm mb-1">Current Time</p>
                    <p class="text-2xl font-bold" id="current-time"></p>
                </div>
            </div>
        </div>

        <!-- Clock In/Out Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Clock In Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-full p-3 mr-4">
                            <i class="fas fa-sign-in-alt text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Clock In</h3>
                            <p class="text-green-100">Start your work day</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('attendance.clock-in') }}">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-user text-green-500 mr-2"></i>Select Employee
                            </label>
                            <select name="employee_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300" required>
                                <option value="">Choose an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                            <i class="fas fa-sign-in-alt mr-2"></i>Clock In Now
                        </button>
                    </form>
                </div>
            </div>

            <!-- Clock Out Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-rose-600 p-6 text-white">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-full p-3 mr-4">
                            <i class="fas fa-sign-out-alt text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Clock Out</h3>
                            <p class="text-red-100">End your work day</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('attendance.clock-out') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-user text-red-500 mr-2"></i>Select Employee
                            </label>
                            <select name="employee_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-300" required>
                                <option value="">Choose an employee...</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                            <i class="fas fa-sign-out-alt mr-2"></i>Clock Out Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                <div class="flex items-center">
                    <div class="bg-white/20 rounded-full p-3 mr-4">
                        <i class="fas fa-filter text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Attendance Filters</h3>
                        <p class="text-blue-100">Filter and search attendance records</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('attendance.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>From Date
                            </label>
                            <input type="date" name="date_from" value="{{ request('date_from', date('Y-m-d')) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>To Date
                            </label>
                            <input type="date" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-blue-500 mr-2"></i>Department
                            </label>
                            <select name="department_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-500 mr-2"></i>Employee
                            </label>
                            <select name="employee_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}" {{ request('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                            <i class="fas fa-filter mr-2"></i>Apply Filter
                        </button>
                        <a href="{{ route('attendance.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold">
                            <i class="fas fa-undo mr-2"></i>Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 rounded-full p-3 mr-4">
                            <i class="fas fa-table text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Attendance Records</h3>
                            <p class="text-indigo-100">View and monitor employee attendance</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-100 text-sm">Total Records</p>
                        <p class="text-2xl font-bold">{{ $attendances->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock In</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock Out</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Working Hours</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-indigo-400 to-purple-500 rounded-lg p-2 mr-3">
                                            <i class="fas fa-calendar text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $attendance->created_at->format('D') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-gradient-to-r from-blue-400 to-purple-500 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                            <span class="text-white font-semibold text-sm">{{ substr($attendance->employee->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->employee->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->employee->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $attendance->employee->department->department_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->clock_in)
                                        <div class="flex items-center">
                                            <div class="bg-green-100 rounded-lg p-2 mr-2">
                                                <i class="fas fa-sign-in-alt text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->clock_in->format('H:i:s') }}</div>
                                                <div class="text-xs text-gray-500">Clock In</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->clock_out)
                                        <div class="flex items-center">
                                            <div class="bg-red-100 rounded-lg p-2 mr-2">
                                                <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->clock_out->format('H:i:s') }}</div>
                                                <div class="text-xs text-gray-500">Clock Out</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusBadges = [];
                                        $statusInfo = [];
                                        
                                        // Check for error status first
                                        if ((isset($attendance->clock_in_status) && $attendance->clock_in_status === 'Error') || 
                                            (isset($attendance->clock_out_status) && $attendance->clock_out_status === 'Error')) {
                                            $statusBadges[] = '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-exclamation-triangle mr-1"></i>Error</span>';
                                            $statusInfo[] = 'Time calculation error';
                                        } else {
                                            // Clock in status
                                            if ($attendance->clock_in_status) {
                                                $clockInClass = match($attendance->clock_in_status) {
                                                    'Late' => 'bg-red-100 text-red-800',
                                                    'On Time' => 'bg-green-100 text-green-800',
                                                    default => 'bg-blue-100 text-blue-800'
                                                };
                                                $clockInIcon = match($attendance->clock_in_status) {
                                                    'Late' => 'fas fa-exclamation-triangle',
                                                    'On Time' => 'fas fa-check-circle',
                                                    default => 'fas fa-info-circle'
                                                };
                                                $statusBadges[] = '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ' . $clockInClass . '"><i class="' . $clockInIcon . ' mr-1"></i>' . $attendance->clock_in_status . '</span>';
                                                
                                                if ($attendance->clock_in_status === 'Late' && $attendance->late_minutes > 0) {
                                                    $statusInfo[] = $attendance->late_minutes . ' min late';
                                                }
                                            }
                                            
                                            // Clock out status
                                            if ($attendance->clock_out_status) {
                                                $clockOutClass = match($attendance->clock_out_status) {
                                                    'Early' => 'bg-yellow-100 text-yellow-800',
                                                    'On Time' => 'bg-green-100 text-green-800',
                                                    default => 'bg-blue-100 text-blue-800'
                                                };
                                                $clockOutIcon = match($attendance->clock_out_status) {
                                                    'Early' => 'fas fa-clock',
                                                    'On Time' => 'fas fa-check-circle',
                                                    default => 'fas fa-info-circle'
                                                };
                                                $statusBadges[] = '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ' . $clockOutClass . '"><i class="' . $clockOutIcon . ' mr-1"></i>' . $attendance->clock_out_status . '</span>';
                                                
                                                if ($attendance->clock_out_status === 'Early' && $attendance->early_minutes > 0) {
                                                    $statusInfo[] = $attendance->early_minutes . ' min early';
                                                }
                                            }
                                            
                                            // If no specific status, show overall status
                                            if (empty($statusBadges)) {
                                                $overallClass = match($attendance->overall_status ?? 'Unknown') {
                                                    'Complete' => 'bg-green-100 text-green-800',
                                                    'Clocked In' => 'bg-blue-100 text-blue-800',
                                                    'Late/Early' => 'bg-orange-100 text-orange-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                                $overallIcon = match($attendance->overall_status ?? 'Unknown') {
                                                    'Complete' => 'fas fa-check-circle',
                                                    'Clocked In' => 'fas fa-clock',
                                                    'Late/Early' => 'fas fa-exclamation-triangle',
                                                    default => 'fas fa-question-circle'
                                                };
                                                $statusBadges[] = '<span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ' . $overallClass . '"><i class="' . $overallIcon . ' mr-1"></i>' . ($attendance->overall_status ?? 'Unknown') . '</span>';
                                            }
                                        }
                                    @endphp
                                    
                                    <div class="flex flex-wrap gap-1">
                                        {!! implode(' ', $statusBadges) !!}
                                    </div>
                                    
                                    @if (!empty($statusInfo))
                                        <div class="mt-1">
                                            <small class="text-gray-600">{{ implode(', ', $statusInfo) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->working_hours !== null)
                                        <div class="flex items-center">
                                            <div class="bg-purple-100 rounded-lg p-2 mr-2">
                                                <i class="fas fa-hourglass-half text-purple-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->working_hours }}h {{ $attendance->working_minutes ?? 0 }}m</div>
                                                <div class="text-xs text-gray-500">Total Time</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                            <i class="fas fa-clock text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">No attendance records found</p>
                                        <p class="text-gray-400 text-sm mb-4">Try adjusting your filters or check back later</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update current time with enhanced formatting
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
        
        $('#current-time').html(`
            <div class="text-2xl font-bold">${timeString}</div>
            <div class="text-sm text-purple-200">${dateString}</div>
        `);
    }
    
    updateTime();
    setInterval(updateTime, 1000);
    
    // Add loading animation effect
    $('.bg-white').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        });
        
        setTimeout(() => {
            $(this).css({
                'opacity': '1',
                'transform': 'translateY(0)',
                'transition': 'all 0.6s ease'
            });
        }, index * 150);
    });
    
    // Enhanced form submissions
    $('form[method="POST"]').on('submit', function(e) {
        const button = $(this).find('button[type="submit"]');
        const originalText = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
        button.prop('disabled', true);
        
        // Re-enable after 3 seconds if form hasn't submitted
        setTimeout(() => {
            button.html(originalText);
            button.prop('disabled', false);
        }, 3000);
    });
    
    // Success notifications for clock in/out
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    @endif
});
</script>
@endpush