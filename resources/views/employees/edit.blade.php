@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <div class="space-y-8 p-6">
        <!-- Header dengan gradient background -->
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Edit Employee</h1>
                    <p class="text-green-100 text-lg">Update employee information for {{ $employee->name }}</p>
                </div>
                <a href="{{ route('employees.index') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>

        <!-- Employee Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-full w-16 h-16 flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">{{ substr($employee->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $employee->name }}</h3>
                    <p class="text-gray-600">Employee ID: {{ $employee->employee_id }}</p>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mt-1">
                        <i class="fas fa-building mr-1"></i>
                        {{ $employee->department ? $employee->department->department_name : 'No Department' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-full p-3 mr-4">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Update Employee Information</h3>
                        <p class="text-gray-600">Modify the details below to update employee record</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('employees.update', $employee->employee_id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2 text-gray-500"></i>Employee ID
                            </label>
                            <input type="text" 
                                   name="employee_id" 
                                   id="employee_id" 
                                   value="{{ $employee->employee_id }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed" 
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Employee ID cannot be changed</p>
                        </div>

                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-green-500"></i>Employee Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $employee->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 ring-2 ring-red-200 @enderror" 
                                   placeholder="Enter employee name"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building mr-2 text-blue-500"></i>Department
                            </label>
                            <select name="department_id" 
                                    id="department_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('department_id') border-red-500 ring-2 ring-red-200 @enderror" 
                                    required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 space-y-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>Address
                        </label>
                        <textarea name="address" 
                                  id="address" 
                                  rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 @error('address') border-red-500 ring-2 ring-red-200 @enderror" 
                                  placeholder="Enter employee address"
                                  required>{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 mt-10 pt-6 border-t border-gray-200">
                        <a href="{{ route('employees.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-sm">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
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
        }, index * 100);
    });
    
    // Enhanced form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('border-red-500 ring-2 ring-red-200');
            } else {
                $(this).removeClass('border-red-500 ring-2 ring-red-200');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all required fields!',
                icon: 'error',
                confirmButtonColor: '#ef4444'
            });
        }
    });
    
    // Remove error styling on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('border-red-500 ring-2 ring-red-200');
    });
});
</script>
@endpush
