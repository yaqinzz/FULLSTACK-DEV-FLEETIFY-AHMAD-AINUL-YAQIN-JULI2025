<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-soft': 'pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation dengan gradient dan shadow -->
    <nav class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white shadow-2xl sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                            Attendance System
                        </h1>
                    </div>
                    <div class="hidden md:ml-8 md:flex md:space-x-1">
                        <a href="{{ route('dashboard') }}" class="nav-link group relative px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            <span>Dashboard</span>
                            @if(request()->routeIs('dashboard'))
                                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white rounded-full"></div>
                            @endif
                        </a>
                        <a href="{{ route('departments.index') }}" class="nav-link group relative px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('departments.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-building mr-2"></i>
                            <span>Departments</span>
                            @if(request()->routeIs('departments.*'))
                                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white rounded-full"></div>
                            @endif
                        </a>
                        <a href="{{ route('employees.index') }}" class="nav-link group relative px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('employees.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-users mr-2"></i>
                            <span>Employees</span>
                            @if(request()->routeIs('employees.*'))
                                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white rounded-full"></div>
                            @endif
                        </a>
                        <a href="{{ route('attendance.index') }}" class="nav-link group relative px-4 py-2 rounded-lg transition-all duration-300 {{ request()->routeIs('attendance.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Attendance</span>
                            @if(request()->routeIs('attendance.*'))
                                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white rounded-full"></div>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-blue-100 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition-all duration-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden bg-white/10 backdrop-blur-sm">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('departments.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-building mr-2"></i>Departments
                </a>
                <a href="{{ route('employees.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-users mr-2"></i>Employees
                </a>
                <a href="{{ route('attendance.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-100 hover:text-white hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-clock mr-2"></i>Attendance
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages dengan animasi -->
    <div id="flash-messages" class="relative z-40">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg transform transition-all duration-500 ease-in-out animate-fade-in-up flex items-center">
                    <div class="bg-white/20 rounded-full p-2 mr-4">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="bg-gradient-to-r from-red-500 to-rose-500 text-white px-6 py-4 rounded-xl shadow-lg transform transition-all duration-500 ease-in-out animate-fade-in-up flex items-center">
                    <div class="bg-white/20 rounded-full p-2 mr-4">
                        <i class="fas fa-exclamation-circle text-white"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-4 rounded-xl shadow-lg transform transition-all duration-500 ease-in-out animate-fade-in-up flex items-center">
                    <div class="bg-white/20 rounded-full p-2 mr-4">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div class="flex-1">
                        <span class="font-medium">{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <script>
        // Mobile menu toggle
        $(document).ready(function() {
            $('.mobile-menu-button').click(function() {
                $('.mobile-menu').toggleClass('hidden');
            });
            
            // Auto hide flash messages after 5 seconds
            setTimeout(function() {
                $('#flash-messages > div').each(function() {
                    $(this).fadeOut(500, function() {
                        $(this).remove();
                    });
                });
            }, 5000);
            
            // Add smooth scroll behavior
            $('html').css('scroll-behavior', 'smooth');
        });
    </script>

    @stack('scripts')
</body>
</html>
