<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::with('employees')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name'   => 'required|string|max:255',
            'max_clock_in_time' => 'required|date_format:H:i',
            'max_clock_out_time' => 'required|date_format:H:i'
        ]);

        // Normalisasi ke format HH:MM:SS
        $validated['max_clock_in_time']  = $validated['max_clock_in_time'] . ':00';
        $validated['max_clock_out_time'] = $validated['max_clock_out_time'] . ':00';

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully');
    }

    /**
     * Display the specified resource.
     * (Opsional — buat view show.blade.php kalau mau detail per department)
     */
    public function show(Department $department)
    {
        $department->load('employees');
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_name'   => 'required|string|max:255',
            'max_clock_in_time' => 'required|date_format:H:i',
            'max_clock_out_time' => 'required|date_format:H:i'
        ]);

        $validated['max_clock_in_time']  = $validated['max_clock_in_time'] . ':00';
        $validated['max_clock_out_time'] = $validated['max_clock_out_time'] . ':00';

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            // Check if department has employees
            if ($department->employees()->exists()) {
                return redirect()->route('departments.index')->with('error', 'Cannot delete department. Department has employees assigned to it.');
            }

            $department->delete();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department. Department may have related records.');
        }
    }
}
