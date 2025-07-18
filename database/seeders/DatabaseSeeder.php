<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create departments
        $itDept = Department::create([
            'department_name' => 'IT Department',
            'max_clock_in_time' => '08:00:00',
            'max_clock_out_time' => '17:00:00'
        ]);

        $hrDept = Department::create([
            'department_name' => 'HR Department',
            'max_clock_in_time' => '08:30:00',
            'max_clock_out_time' => '17:30:00'
        ]);

        $financeDept = Department::create([
            'department_name' => 'Finance Department',
            'max_clock_in_time' => '08:00:00',
            'max_clock_out_time' => '17:00:00'
        ]);

        // Create employees
        Employee::create([
            'employee_id' => 'EMP0001',
            'name' => 'John Doe',
            'department_id' => $itDept->id,
            'address' => 'Jl. Sudirman No. 123, Jakarta'
        ]);

        Employee::create([
            'employee_id' => 'EMP0002',
            'name' => 'Jane Smith',
            'department_id' => $hrDept->id,
            'address' => 'Jl. Thamrin No. 456, Jakarta'
        ]);

        Employee::create([
            'employee_id' => 'EMP0003',
            'name' => 'Bob Johnson',
            'department_id' => $financeDept->id,
            'address' => 'Jl. Gatot Subroto No. 789, Jakarta'
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
