<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('department')->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string'
        ]);

        // Auto-generate employee ID
        $lastEmployee = Employee::orderBy('employee_id', 'desc')->first();
        if ($lastEmployee) {
            $lastNumber = (int) substr($lastEmployee->employee_id, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $employeeId = 'EMP' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        Employee::create([
            'employee_id' => $employeeId,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'address' => $request->address
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('department', 'attendances');
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string'
        ]);

        $employee->update($request->only(['name', 'address', 'department_id']));

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            // With cascade delete enabled, we can safely delete the employee
            // All related attendances and attendance_histories will be deleted automatically
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee and all related records deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
}
