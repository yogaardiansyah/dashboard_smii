<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#000000">

    <meta name="turbolinks-visit-control" content="reload">

    {{-- <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/sinarmeadow.png') }}">

    <link rel="manifest" href="{{ asset('/manifest.json') }}"> --}}
    <link rel="icon" href="{{ url('assets/images/sinarmeadow.webp') }}">

    <title>{{ 'Operational Dashboard SMII' }} - @yield('title')</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor-css/figtree.css') }}">

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/vendors_css.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/font-awesome-6.4.css">

    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/tailwind.min.css">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/horizontal-menu.css?v={{ file_exists(public_path('assets/src/css/horizontal-menu.css')) ? filemtime(public_path('assets/src/css/horizontal-menu.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/style.css?v={{ file_exists(public_path('assets/src/css/style.css')) ? filemtime(public_path('assets/src/css/style.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/skin_color.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/src/css/custom.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor-css/jquery-ui-1.13.2.css') }}">
    <link href="{{ asset('assets/vendor-css/sweetalert2-11.17.2.min.css') }}?v={{ file_exists(public_path('assets/vendor-css/sweetalert2-11.17.2.min.css')) ? filemtime(public_path('assets/vendor-css/sweetalert2-11.17.2.min.css')) : time() }}" rel="stylesheet">

    @stack('css')

    <style>
        .turbolinks-progress-bar {
            height: 3px;
            background-color: #c0a01f;
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            z-index: 9999;
            transition: width 300ms ease-out, opacity 150ms 150ms ease-in;
        }

        /* Force SweetAlert (v1 & v2) to always be above any modals */
        .swal2-container,
        div.swal2-container,
        div:where(.swal2-container),
        .swal2-container.swal2-backdrop-show,
        .swal2-container.swal2-shown,
        body.swal2-shown .swal2-container,
        .sweet-overlay,
        .sweet-alert {
            z-index: 2147483647 !important;
            position: fixed !important;
        }

        /* Custom INTRA SMII Branded Preloader */
        #loader {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #090e1a 0%, #0b1329 50%, #0f172a 100%);
            overflow: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        #loader.loaded,
        #loader[style*="display: none"],
        #loader[style*="opacity: 0"] {
            pointer-events: none !important;
            visibility: hidden !important;
        }


        .custom-loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            text-align: center;
        }

        .loader-logo-wrapper {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-spinner-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid rgba(192, 160, 31, 0.15);
            border-top: 3px solid #c0a01f;
            border-right: 3px solid #38bdf8;
            animation: loaderSpin 1.2s cubic-bezier(0.5, 0.1, 0.5, 0.9) infinite;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.2), 0 0 15px rgba(192, 160, 31, 0.2);
        }

        .loader-logo-img {
            height: 54px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.5));
            animation: logoPulse 2s ease-in-out infinite alternate;
        }

        .loader-brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #ffffff 0%, #fde047 50%, #c0a01f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            margin: 0;
        }

        .loader-progress-bar-wrap {
            width: 140px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .loader-progress-bar-line {
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, #c0a01f 0%, #38bdf8 100%);
            border-radius: 999px;
            position: absolute;
            left: -40%;
            animation: progressSlide 1.5s ease-in-out infinite;
        }

        .loader-status-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.65);
            letter-spacing: 0.5px;
            font-weight: 500;
            margin: 0;
        }

        @keyframes loaderSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes logoPulse {
            0% {
                transform: scale(0.95);
                filter: drop-shadow(0 4px 10px rgba(192, 160, 31, 0.3));
            }

            100% {
                transform: scale(1.05);
                filter: drop-shadow(0 6px 20px rgba(56, 189, 248, 0.5));
            }
        }

        @keyframes progressSlide {
            0% {
                left: -40%;
                width: 30%;
            }

            50% {
                width: 60%;
            }

            100% {
                left: 100%;
                width: 30%;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout-top-nav light-skin theme-primary fixed-manu">

    <div class="wrapper">
        <div id="loader">
            <div class="custom-loader-content">
                <div class="loader-logo-wrapper">
                    <div class="loader-spinner-ring"></div>
                    <img src="{{ asset('assets/images/sinarmeadow.webp') }}" alt="Logo Intra SMII"
                        class="loader-logo-img" onerror="this.src='{{ asset('sinarmeadow.png') }}'">
                </div>
                <h2 class="loader-brand-title">OPERATIONAL DASHBOARD SMII</h2>
                <div class="loader-progress-bar-wrap">
                    <div class="loader-progress-bar-line"></div>
                </div>
                <p class="loader-status-text">Memuat Portal Operational Dashboard SMII...</p>
            </div>
        </div>
        @include('layouts.partials.header')

        @include('layouts.partials.sidebar')
        <div class="content-wrapper">
            <div class="px-4 md:px-0">
                {{ $slot }}
            </div>
        </div>


        @include('layouts.partials.footer')

    </div>




    <!-- Vendor JS -->
    <script src="{{ asset('assets') }}/src/js/vendors.min.js"></script>
    <script src="{{ asset('assets') }}/icons/feather-icons/feather.min.js"></script>

    <script src="{{ asset('assets') }}/src/js/tailwind.min.js"></script>
    <script src="{{ asset('assets/datepicker/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets') }}/src/js/jquery.smartmenus.js"></script>
    <script src="{{ asset('assets') }}/src/js/menus.js?v={{ file_exists(public_path('assets/src/js/menus.js')) ? filemtime(public_path('assets/src/js/menus.js')) : time() }}"></script>
    <script src="{{ asset('assets') }}/src/js/template.js?v={{ file_exists(public_path('assets/src/js/template.js')) ? filemtime(public_path('assets/src/js/template.js')) : time() }}"></script>

    <script src="{{ asset('assets/vendor-js/sweetalert2-11.17.2.all.min.js') }}?v={{ file_exists(public_path('assets/vendor-js/sweetalert2-11.17.2.all.min.js')) ? filemtime(public_path('assets/vendor-js/sweetalert2-11.17.2.all.min.js')) : time() }}"></script>

    <style id="swal2-top-priority">
        .swal2-container,
        div.swal2-container,
        div:where(.swal2-container),
        .swal2-container.swal2-backdrop-show,
        .swal2-container.swal2-shown,
        body.swal2-shown .swal2-container,
        .sweet-overlay,
        .sweet-alert {
            z-index: 2147483647 !important;
            position: fixed !important;
        }
    </style>

    <script>
        // Universal modal and SweetAlert priority manager
        (function() {
            // Monkey-patch Swal.fire so that modal dialogs automatically target any active modal
            function patchSwal() {
                if (window.Swal && !window.Swal._isPatchedForModals) {
                    const originalFire = window.Swal.fire.bind(window.Swal);
                    window.Swal.fire = function(...args) {
                        const activeModal = document.querySelector('.pl-modal-overlay.active, .image-preview-overlay[style*="display: flex"]');
                        if (activeModal && args[0] && typeof args[0] === 'object' && !args[0].toast && !args[0].target) {
                            args[0].target = activeModal;
                        }
                        const res = originalFire(...args);
                        requestAnimationFrame(() => {
                            document.querySelectorAll('.swal2-container').forEach(function(swal) {
                                swal.style.setProperty('z-index', '2147483647', 'important');
                                swal.style.setProperty('position', 'fixed', 'important');
                            });
                        });
                        return res;
                    };
                    window.Swal._isPatchedForModals = true;
                }
            }

            patchSwal();
            document.addEventListener('DOMContentLoaded', patchSwal);

            function promoteModalsAndSwal() {
                // Ensure pl-modal-overlay modals are direct children of body so they escape any parent overflow / transform
                document.querySelectorAll('.pl-modal-overlay').forEach(function(modal) {
                    if (modal.parentElement && modal.parentElement !== document.body) {
                        document.body.appendChild(modal);
                    }
                });

                // Ensure SweetAlert2 always has the maximum possible z-index
                document.querySelectorAll('.swal2-container').forEach(function(swal) {
                    swal.style.setProperty('z-index', '2147483647', 'important');
                    swal.style.setProperty('position', 'fixed', 'important');
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', promoteModalsAndSwal);
            } else {
                promoteModalsAndSwal();
            }

            // Real-time observer to catch newly added SweetAlert containers or modals
            const observer = new MutationObserver(function(mutations) {
                patchSwal();
                for (let i = 0; i < mutations.length; i++) {
                    const added = mutations[i].addedNodes;
                    for (let j = 0; j < added.length; j++) {
                        const node = added[j];
                        if (node.nodeType === 1) {
                            if (node.classList && node.classList.contains('swal2-container')) {
                                node.style.setProperty('z-index', '2147483647', 'important');
                                node.style.setProperty('position', 'fixed', 'important');
                            }
                            if (node.classList && node.classList.contains('pl-modal-overlay')) {
                                if (node.parentElement !== document.body) {
                                    document.body.appendChild(node);
                                }
                            }
                        }
                    }
                }
            });

            observer.observe(document.documentElement, { childList: true, subtree: true });
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fullscreenBtn = document.getElementById('fullscreenButton');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');

                    if (!document.fullscreenElement) {
                        // Masuk ke Mode Fullscreen
                        document.documentElement.requestFullscreen().then(() => {
                            if (icon) {
                                icon.classList.remove('fa-expand');
                                icon.classList.add('fa-compress');
                            }
                        }).catch(err => {
                            console.error(`Gagal aktifkan fullscreen: ${err.message}`);
                        });
                    } else {
                        // Keluar dari Mode Fullscreen
                        if (document.exitFullscreen) {
                            document.exitFullscreen().then(() => {
                                if (icon) {
                                    icon.classList.remove('fa-compress');
                                    icon.classList.add('fa-expand');
                                }
                            });
                        }
                    }
                });
                // Menangani kejadian saat pengguna menekan tombol ESC di keyboard
                document.addEventListener('fullscreenchange', function() {
                    const icon = fullscreenBtn.querySelector('i');
                    if (!document.fullscreenElement && icon) {
                        icon.classList.remove('fa-compress');
                        icon.classList.add('fa-expand');
                    }
                });
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
