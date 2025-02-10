<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @dump(Auth::guard('web')->check()) --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap">
    <meta name="user-role"
        content="{{ Auth::guard('admin')->check() ? 'admin' : (Auth::guard('web')->check() ? 'user' : 'guest') }}">
    {{-- <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" /> --}}
    <!-- Role-Specific Meta Tag -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle2.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" /> --}}
    {{-- @guest
    @else
        @if (Auth::guard('admin')->check())
            <meta name="user-role" content="admin">
        @elseif(Auth::guard('web')->check())
            <meta name="user-role" content="user">
            <link rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap">
            <link rel="stylesheet" crossorigin="" href="@assets/css/dashboard.css">
        @else
            <meta name="user-role" content="guest">
        @endif
    @endguest --}}
    <!-- Conditional Styles for Admin -->
    @if (Auth::guard('admin')->check())
        {{-- <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet"> --}}
    @endif

    <title>
        @if (Auth::guard('admin')->check())
            Intracard Admin Dashboard
        @elseif(Auth::guard('web')->check())
            Intracard User Dashboard || My Account
        @else
            Intracard Login
        @endif
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Kodchasan" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <!-- Replace with your favicon URL -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">


    <!-- Global Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite Integration for Vue -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

</head>

<body>

    <div id="app"></div>

    <!-- Add this in your <head> or dynamically -->
    <!-- Global JavaScript -->
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-gauges@2.1.7/gauge.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!-- Conditional Scripts for Admin -->
    @if (Auth::guard('admin')->check())
        {{-- <script src="{{ asset('assets/js/admin.js') }}"></script> --}}
    @endif
</body>

</html>
