<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('sinarmeadow.png') }}">
    <title>{{ config('app.name', 'INTRA SMII') }} - @yield('code', 'Error') | @yield('title', 'Error')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Custom Error Page Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>
<body class="error-page-body">

    <!-- Ambient Glowing Backdrops (Matching Welcome Dashboard Theme) -->
    <div class="glow-flare flare-cyan"></div>
    <div class="glow-flare flare-gold"></div>
    <div class="glow-flare flare-indigo"></div>
    <div class="error-bg-pattern"></div>

    <!-- Central Error Card Container -->
    <div class="error-card-container">
        <!-- Internal Glowing Flares -->
        <div class="error-card-flare"></div>
        <div class="error-card-flare-right"></div>
        
        <!-- Brand Header -->
        <a href="{{ url('/') }}" class="error-brand">
            <img src="{{ asset('sinarmeadow.png') }}" alt="Logo Sinar Meadow" onerror="this.style.display='none'">
            <span class="error-brand-title">INTRA SMII</span>
        </a>

        <!-- Animated SVG Illustration -->
        <div class="error-illustration">
            @hasSection('illustration')
                @yield('illustration')
            @else
                <!-- Default Error Globe/Radar SVG -->
                <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="100" r="80" stroke="#38bdf8" stroke-width="3" stroke-dasharray="8 8" class="spin-gear" />
                    <circle cx="100" cy="100" r="55" fill="rgba(56, 189, 248, 0.15)" stroke="#c0a01f" stroke-width="2" class="pulse-element" />
                    <path d="M75 100 L125 100 M100 75 L100 125" stroke="#ffffff" stroke-width="4" stroke-linecap="round" />
                    <circle cx="100" cy="100" r="10" fill="#fde047" />
                </svg>
            @endif
        </div>

        <!-- Error Code Badge Chip -->
        <div class="error-badge">
            @hasSection('badge_text')
                @yield('badge_text')
            @else
                ERROR @yield('code', '500')
            @endif
        </div>

        <!-- Error Main Title & Message -->
        <h1 class="error-title">
            @yield('message', __('Something Went Wrong'))
        </h1>

        <p class="error-description">
            @yield('message2', __('An unexpected error occurred. Please try again or return to the dashboard.'))
        </p>

        <!-- Action Buttons -->
        <div class="error-actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="{{ route('dashboard') }}" class="error-btn error-btn-primary">
                    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span>{{ __('Ke Dashboard') }}</span>
                </a>
                <button onclick="window.history.back()" class="error-btn error-btn-secondary">
                    <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                    <span>{{ __('Halaman Sebelumnya') }}</span>
                </button>
                <button onclick="window.location.reload()" class="error-btn error-btn-secondary">
                    <svg viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                    <span>{{ __('Muat Ulang') }}</span>
                </button>
            @endif
        </div>

    </div>

    <!-- Footer -->
    <footer class="error-footer">
        &copy; {{ date('Y') }} PT Sinar Meadow International Indonesia. All rights reserved.
    </footer>

</body>
</html>
