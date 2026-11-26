@php
    $clinicRole = session('clinic_role', 'Admin');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClinicCare — Appointment System')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cliniccare.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="app-shell">
        @include('layouts.sidebar')

        <div class="app-main">
            @include('layouts.navbar')

            <main class="content">
                @yield('content')
                @yield('contents')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
