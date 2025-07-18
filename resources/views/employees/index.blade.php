@extends('layouts.app')

@section('title', 'Employees Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <div class="space-y-8 p-6">
        <!-- Header dengan gradient background -->
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Employees Management</h1>
                    <p class="text-green-100 text-lg">Manage employee information and departments</p>
                </div>
                <a href="{{ route('employees.create') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Add Employee
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-full p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Employees</p>
                        <p class="text-3xl font-bold text-green-600">{{ $employees->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-full p-3">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Departments</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $employees->groupBy('department_id')->count() }}</p>
                    </div>
                </div>
            </div>   
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-full p-3 mr-4">
                        <i class="fas fa-filter text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Filter Employees</h3>
                        <p class="text-gray-600">Search and filter employee list</p>
                    </div>
                </div>
                <button id="clearFilters" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-xl transition-all duration-300">
                    <i class="fas fa-times mr-2"></i>Clear Filters
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Search by Name/ID -->
                <div class="space-y-2">
                    <label for="searchEmployee" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-search mr-2 text-blue-500"></i>Search Employee
                    </label>
                    <input type="text" 
                           id="searchEmployee" 
                           placeholder="Search by name or employee ID..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                </div>

                <!-- Filter by Department -->
                <div class="space-y-2">
                    <label for="filterDepartment" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-building mr-2 text-green-500"></i>Department
                    </label>
                    <select id="filterDepartment" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                        <option value="">All Departments</option>
                        @foreach($employees->groupBy('department_id') as $departmentId => $employeeGroup)
                            @if($employeeGroup->first()->department)
                                <option value="{{ $departmentId }}">{{ $employeeGroup->first()->department->department_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="space-y-2">
                    <label for="sortEmployee" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-sort mr-2 text-purple-500"></i>Sort By
                    </label>
                    <select id="sortEmployee" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        <option value="name_asc">Name (A-Z)</option>
                        <option value="name_desc">Name (Z-A)</option>
                        <option value="id_asc">Employee ID (A-Z)</option>
                        <option value="id_desc">Employee ID (Z-A)</option>
                        <option value="department_asc">Department (A-Z)</option>
                    </select>
                </div>
            </div>

            <!-- Filter Results Info -->
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between">
                    <span id="filterInfo" class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>Showing <span id="visibleCount">{{ $employees->count() }}</span> of {{ $employees->count() }} employees
                    </span>
                    <div class="flex space-x-2">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-eye mr-1"></i><span id="visibleCountBadge">{{ $employees->count() }}</span> Visible
                        </span>
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            <i class="fas fa-eye-slash mr-1"></i><span id="hiddenCountBadge">0</span> Hidden
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table dengan design modern -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">All Employees</h3>
                        <p class="text-gray-600">Manage and view all employee information</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-full p-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Employee ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-lg">{{ substr($employee->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-sm text-gray-500">Employee</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-id-card mr-1"></i>
                                    {{ $employee->employee_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($employee->department)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $employee->department->department_name }}
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                        <i class="fas fa-question mr-1"></i>
                                        No Department
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $employee->address }}">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    {{ $employee->address }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('employees.edit', $employee->employee_id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-sm">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee->employee_id) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-sm">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">No employees found</p>
                                    <p class="text-gray-400 text-sm mb-4">Add your first employee to get started</p>
                                    <a href="{{ route('employees.create') }}" class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-300">
                                        <i class="fas fa-plus mr-2"></i>Add First Employee
                                    </a>
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
    
    // Enhanced delete confirmation
    $('form[method="POST"]').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the employee!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Filter functionality
    const $searchInput = $('#searchEmployee');
    const $departmentFilter = $('#filterDepartment');
    const $sortSelect = $('#sortEmployee');
    const $clearFilters = $('#clearFilters');
    const $tableRows = $('tbody tr:not(.empty-state)');
    const totalEmployees = $tableRows.length;

    function updateFilterInfo() {
        const visibleRows = $tableRows.filter(':visible').length;
        const hiddenRows = totalEmployees - visibleRows;
        
        $('#visibleCount').text(visibleRows);
        $('#visibleCountBadge').text(visibleRows);
        $('#hiddenCountBadge').text(hiddenRows);
        
        // Show/hide empty state
        if (visibleRows === 0) {
            $('.empty-state').show();
            $('.empty-state td').html(`
                <div class="flex flex-col items-center py-8">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg font-medium">No employees found</p>
                    <p class="text-gray-400 text-sm mb-4">Try adjusting your search or filter criteria</p>
                    <button id="resetFiltersFromEmpty" class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-300">
                        <i class="fas fa-refresh mr-2"></i>Reset Filters
                    </button>
                </div>
            `);
        } else {
            $('.empty-state').hide();
        }
    }

    function applyFilters() {
        const searchTerm = $searchInput.val().toLowerCase();
        const departmentFilter = $departmentFilter.val();
        
        $tableRows.each(function() {
            const $row = $(this);
            if ($row.hasClass('empty-state')) return;
            
            const employeeName = $row.find('td:first .text-sm.font-bold').text().toLowerCase();
            const employeeId = $row.find('td:nth-child(2) span').text().toLowerCase();
            const departmentId = $row.find('td:nth-child(3) span').data('department-id') || '';
            
            let showRow = true;
            
            // Search filter
            if (searchTerm && !employeeName.includes(searchTerm) && !employeeId.includes(searchTerm)) {
                showRow = false;
            }
            
            // Department filter
            if (departmentFilter && departmentId.toString() !== departmentFilter) {
                showRow = false;
            }
            
            $row.toggle(showRow);
        });
        
        applySorting();
        updateFilterInfo();
    }

    function applySorting() {
        const sortValue = $sortSelect.val();
        const $tbody = $('tbody');
        const $visibleRows = $tableRows.filter(':visible').not('.empty-state');
        
        const sortedRows = $visibleRows.sort(function(a, b) {
            let aVal, bVal;
            
            switch(sortValue) {
                case 'name_asc':
                case 'name_desc':
                    aVal = $(a).find('td:first .text-sm.font-bold').text().toLowerCase();
                    bVal = $(b).find('td:first .text-sm.font-bold').text().toLowerCase();
                    break;
                case 'id_asc':
                case 'id_desc':
                    aVal = $(a).find('td:nth-child(2) span').text().toLowerCase();
                    bVal = $(b).find('td:nth-child(2) span').text().toLowerCase();
                    break;
                case 'department_asc':
                    aVal = $(a).find('td:nth-child(3) span').text().toLowerCase();
                    bVal = $(b).find('td:nth-child(3) span').text().toLowerCase();
                    break;
            }
            
            if (sortValue.includes('_desc')) {
                return bVal.localeCompare(aVal);
            } else {
                return aVal.localeCompare(bVal);
            }
        });
        
        // Re-append sorted rows
        sortedRows.appendTo($tbody);
        
        // Add empty state at the end if needed
        if ($('.empty-state').length) {
            $('.empty-state').appendTo($tbody);
        }
    }

    // Event listeners
    $searchInput.on('input', function() {
        applyFilters();
    });

    $departmentFilter.on('change', function() {
        applyFilters();
    });

    $sortSelect.on('change', function() {
        applySorting();
    });

    $clearFilters.on('click', function() {
        $searchInput.val('');
        $departmentFilter.val('');
        $sortSelect.val('name_asc');
        applyFilters();
        
        // Show success message
        Swal.fire({
            title: 'Filters Cleared!',
            text: 'All filters have been reset',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });

    // Reset filters from empty state
    $(document).on('click', '#resetFiltersFromEmpty', function() {
        $clearFilters.click();
    });

    // Add department IDs to department spans for filtering
    @foreach($employees as $employee)
        @if($employee->department)
            $('tbody tr').eq({{ $loop->index }}).find('td:nth-child(3) span').attr('data-department-id', '{{ $employee->department_id }}');
        @endif
    @endforeach

    // Initialize filter info
    updateFilterInfo();

    // Add search highlight functionality
    function highlightSearchTerm(text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }

    $searchInput.on('input', function() {
        const searchTerm = $(this).val();
        
        // Remove existing highlights
        $('mark').each(function() {
            const parent = $(this).parent();
            parent.html(parent.text());
        });
        
        if (searchTerm) {
            $tableRows.each(function() {
                const $nameCell = $(this).find('td:first .text-sm.font-bold');
                const $idCell = $(this).find('td:nth-child(2) span');
                
                const nameText = $nameCell.text();
                const idText = $idCell.text();
                
                if (nameText.toLowerCase().includes(searchTerm.toLowerCase())) {
                    $nameCell.html(highlightSearchTerm(nameText, searchTerm));
                }
                
                if (idText.toLowerCase().includes(searchTerm.toLowerCase())) {
                    $idCell.html('<i class="fas fa-id-card mr-1"></i>' + highlightSearchTerm(idText, searchTerm));
                }
            });
        }
    });
});
</script>
@endpush
