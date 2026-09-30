<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ url('assets/images/sinarmeadow.png') }}">

    <title>{{ 'Operational Dashboard SMII' }} - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/vendors_css.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/src/css/font-awesome-6.4.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/tailwind.min.css">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/horizontal-menu.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/skin_color.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition theme-primary m-0 p-0 overflow-x-hidden font-sans">
    {{ $slot }}

    <!-- Latest jQuery & Vendors -->
    <script src="{{ asset('assets') }}/ajax/libs/jQuery-slimScroll/1.3.8/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets') }}/src/js/vendors.min.js"></script>
</body>

</html>
