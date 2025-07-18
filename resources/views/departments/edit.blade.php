@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="p-6">
        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Edit Department</h1>
                        <p class="text-blue-100 text-lg">Update department settings and information</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                        <i class="fas fa-edit text-white text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form method="POST" action="{{ route('departments.update', $department->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Department Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-blue-500 mr-2"></i>Department Name
                            </label>
                            <input type="text" 
                                   name="department_name" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300 @error('department_name') border-red-500 @enderror" 
                                   placeholder="Enter department name"
                                   value="{{ old('department_name', $department->department_name) }}"
                                   required>
                            @error('department_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Clock In Time -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-green-500 mr-2"></i>Maximum Clock In Time
                            </label>
                            <div class="relative">
                                <input type="time" 
                                       name="max_clock_in_time" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-300 @error('max_clock_in_time') border-red-500 @enderror"
                                       value="{{ old('max_clock_in_time', \Carbon\Carbon::parse($department->max_clock_in_time)->format('H:i')) }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500">Employees can clock in before this time without being marked late</p>
                            @error('max_clock_in_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Clock Out Time -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-orange-500 mr-2"></i>Maximum Clock Out Time
                            </label>
                            <div class="relative">
                                <input type="time" 
                                       name="max_clock_out_time" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-300 @error('max_clock_out_time') border-red-500 @enderror"
                                       value="{{ old('max_clock_out_time', \Carbon\Carbon::parse($department->max_clock_out_time)->format('H:i')) }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500">Employees should clock out after this time to avoid early departure</p>
                            @error('max_clock_out_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department Info Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-200">
                            <h4 class="font-semibold text-gray-700 mb-2">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Department Information
                            </h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Department ID:</span>
                                    <span class="font-medium text-gray-800">#{{ $department->id }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total Employees:</span>
                                    <span class="font-medium text-gray-800">{{ $department->employees->count() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('departments.index') }}" 
                               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 transform hover:scale-105 font-medium">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg font-medium">
                                <i class="fas fa-save mr-2"></i>Update Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add loading animation
    $('.bg-white').css({
        'opacity': '0',
        'transform': 'translateY(30px)'
    });
    
    setTimeout(() => {
        $('.bg-white').css({
            'opacity': '1',
            'transform': 'translateY(0)',
            'transition': 'all 0.6s ease'
        });
    }, 200);
    
    // Enhanced form validation
    $('form').on('submit', function(e) {
        const button = $(this).find('button[type="submit"]');
        button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');
        button.prop('disabled', true);
    });
    
    // Real-time validation feedback
    $('input').on('blur', function() {
        if ($(this).val()) {
            $(this).removeClass('border-red-500').addClass('border-green-500');
        }
    });
});
</script>
@endpush
