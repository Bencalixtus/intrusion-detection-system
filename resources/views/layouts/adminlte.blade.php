<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('adminlte.title', 'Network Intrusion Detection System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('css')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body">

        <div class="container-fluid">

            <!-- Sidebar Toggle -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right Navbar -->
            <ul class="navbar-nav ms-auto">

                <!-- User Menu -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       data-bs-toggle="dropdown">

                        <i class="fas fa-user-circle me-1"></i>

                        {{ Auth::user()->name }}

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2"></i>
                                Profile
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <form method="POST"
                                  action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="dropdown-item">

                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Logout

                                </button>
                            </form>
                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </nav>


    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow">

        <!-- Brand -->
        <div class="sidebar-brand">

            <a href="{{ route('dashboard') }}"
               class="brand-link text-decoration-none">

                <span class="brand-text fw-light">
                    IDS Monitor
                </span>

            </a>

        </div>


        <!-- Sidebar Menu -->
        <div class="sidebar-wrapper">

            <nav class="mt-2">

                <ul class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu">

                    <!-- Dashboard -->
                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-tachometer-alt"></i>

                            <p>
                                Dashboard
                            </p>

                        </a>

                    </li>


                    <!-- Security Events -->
                    <li class="nav-item">

                        <a href="{{ route('security-events.index') }}"
                           class="nav-link {{ request()->routeIs('security-events.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-shield-alt"></i>

                            <p>
                                Security Events
                            </p>

                        </a>

                    </li>


                    <!-- Profile -->
                    <li class="nav-item">

                        <a href="{{ route('profile.edit') }}"
                           class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-user"></i>

                            <p>
                                Profile
                            </p>

                        </a>

                    </li>

                </ul>

            </nav>

        </div>

    </aside>


    <!-- Main Content -->
    <main class="app-main">

        @hasSection('header')

            <div class="app-content-header">

                <div class="container-fluid">

                    @yield('header')

                </div>

            </div>

        @endif


        <div class="app-content">

            <div class="container-fluid">

                @yield('content')

            </div>

        </div>

    </main>


    <!-- Footer -->
    <footer class="app-footer">

        <strong>
            Network Intrusion Detection and Monitoring System
        </strong>

        <span class="float-end">
            ND2 Project
        </span>

    </footer>

</div>

@stack('scripts')

</body>
</html>