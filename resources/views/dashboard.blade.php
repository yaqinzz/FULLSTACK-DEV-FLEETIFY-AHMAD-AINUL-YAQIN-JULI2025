@extends('layouts.app')

@section('title', 'Dashboard - Attendance System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="space-y-8 p-6">
        <!-- Header dengan gradient background -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Dashboard</h1>
                    <p class="text-blue-100 text-lg">Welcome to the Attendance Management System</p>
                </div>
                <div class="text-right bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-blue-100 text-sm mb-1">Current Time</p>
                    <p class="text-2xl font-bold" id="current-time"></p>
                </div>
            </div>
        </div>

        <!-- Stats Cards dengan hover effect -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Departments</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalDepartments }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Employees</p>
                        <p class="text-3xl font-bold text-green-600">{{ $totalEmployees }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-full p-3">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Check-ins</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $todayCheckins }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:scale-105">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-full p-3">
                        <i class="fas fa-sign-out-alt text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Today's Check-outs</p>
                        <p class="text-3xl font-bold text-red-600">{{ $todayCheckouts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions dengan design yang lebih modern -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="text-center mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-rocket text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Quick Actions</h3>
                    <p class="text-gray-600">Manage your system efficiently</p>
                </div>
                <div class="space-y-4">
                    <a href="{{ route('departments.index') }}" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-center py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-building mr-3"></i>Manage Departments
                    </a>
                    <a href="{{ route('employees.index') }}" class="block w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-users mr-3"></i>Manage Employees
                    </a>
                    <a href="{{ route('attendance.index') }}" class="block w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-center py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-clock mr-3"></i>View Attendance
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 lg:col-span-2 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Attendance</h3>
                        <p class="text-gray-600">Latest employee check-ins today</p>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clock In</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAttendances as $attendance)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-blue-400 to-purple-500 rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                                <span class="text-white font-semibold text-sm">{{ substr($attendance->employee->name, 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $attendance->employee->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $attendance->employee->department->department_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $attendance->clock_in ? $attendance->clock_in->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(isset($attendance->clock_in_status))
                                            @if($attendance->clock_in_status == 'Late')
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 shadow-sm">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    {{ $attendance->clock_in_status }}
                                                </span>
                                            @elseif($attendance->clock_in_status == 'On Time')
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ $attendance->clock_in_status }}
                                                </span>
                                            @else
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 shadow-sm">
                                                    {{ $attendance->clock_in_status }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                                <i class="fas fa-clock text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium">No attendance records for today</p>
                                            <p class="text-gray-400 text-sm">Check back later or add some attendance records</p>
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
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update current time with smooth animation
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        $('#current-time').html(`
            <div class="text-2xl font-bold">${timeString}</div>
            <div class="text-sm text-blue-200">${dateString}</div>
        `);
    }
    
    updateTime();
    setInterval(updateTime, 1000);
    
    // Add smooth animations to cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe all cards
    document.querySelectorAll('.bg-white').forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Add loading animation effect
    setTimeout(() => {
        document.querySelectorAll('.bg-white').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }, 100);
});
</script>
@endpush
