<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Department CRUD
Route::resource('departments', DepartmentController::class);

// Employee CRUD  
Route::resource('employees', EmployeeController::class);

// Attendance Routes
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
Route::put('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
