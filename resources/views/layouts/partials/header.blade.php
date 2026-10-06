<header class="main-header">
    <div class="inside-header">

        <div class="flex items-center logo-box justify-start">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="logo">
                <!-- logo-->
                <div class="logo-lg">
                    <span class="light-logo"><img
                            src="{{ asset('assets/images/logoblack1.webp') }}?v={{ file_exists(public_path('assets/images/logoblack1.webp')) ? filemtime(public_path('assets/images/logoblack1.webp')) : time() }}"
                            width="250" alt="logo"></span>
                    <span class="dark-logo"><img
                            src="{{ asset('assets/images/logoblack1.webp') }}?v={{ file_exists(public_path('assets/images/logoblack1.webp')) ? filemtime(public_path('assets/images/logoblack1.webp')) : time() }}"
                            width="250" alt="logo"></span>
                </div>
            </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- float-left: hamburger toggle + mobile compact logo -->
            <div class="float-left" style="display:flex;align-items:center;gap:8px;">
                <!-- Mobile hamburger (shown only on mobile) -->
                <label class="mobile-hamburger-btn" for="main-menu-state" title="Toggle Menu">
                    <span class="hamburger-bar bar-top"></span>
                    <span class="hamburger-bar bar-middle"></span>
                    <span class="hamburger-bar bar-bottom"></span>
                </label>
                <!-- Mobile compact logo (shown only on mobile) -->
                <a href="{{ route('dashboard') }}" class="mobile-header-logo">
                    <img src="{{ asset('assets/images/logoblack1.webp') }}?v={{ file_exists(public_path('assets/images/logoblack1.webp')) ? filemtime(public_path('assets/images/logoblack1.webp')) : time() }}"
                        alt="logo" style="max-width:130px;height:auto;">
                </a>
            </div>

            <style>
                /* Mobile Hamburger Button Smooth Animation */
                .mobile-hamburger-btn {
                    width: 40px;
                    height: 40px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    gap: 5px;
                    padding: 6px;
                    border-radius: 10px;
                    background: transparent;
                    transition: background-color 0.25s ease, transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
                    cursor: pointer;
                    user-select: none;
                    -webkit-tap-highlight-color: transparent;
                    margin: 0;
                }

                .mobile-hamburger-btn:hover,
                .mobile-hamburger-btn:active {
                    background-color: rgba(59, 130, 246, 0.12);
                }

                .hamburger-bar {
                    display: block;
                    height: 2.5px;
                    border-radius: 4px;
                    background-color: #1e293b;
                    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.25s ease,
                        width 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.25s ease;
                }

                .dark-skin .hamburger-bar {
                    background-color: #f8fafc;
                }

                .bar-top {
                    width: 22px;
                    transform-origin: center;
                }

                .bar-middle {
                    width: 16px;
                    align-self: flex-start;
                    margin-left: 3px;
                }

                .bar-bottom {
                    width: 22px;
                    transform-origin: center;
                }

                /* Active State Morphing Animation */
                body:has(#main-menu-state:checked) .mobile-hamburger-btn,
                .mobile-hamburger-btn.is-active {
                    transform: rotate(180deg);
                }

                body:has(#main-menu-state:checked) .mobile-hamburger-btn .bar-top,
                .mobile-hamburger-btn.is-active .bar-top {
                    transform: translateY(7.5px) rotate(45deg);
                    width: 22px;
                    background-color: #2563eb;
                }

                body:has(#main-menu-state:checked) .mobile-hamburger-btn .bar-middle,
                .mobile-hamburger-btn.is-active .bar-middle {
                    opacity: 0;
                    transform: translateX(-10px);
                    width: 0;
                }

                body:has(#main-menu-state:checked) .mobile-hamburger-btn .bar-bottom,
                .mobile-hamburger-btn.is-active .bar-bottom {
                    transform: translateY(-7.5px) rotate(-45deg);
                    width: 22px;
                    background-color: #2563eb;
                }

                /* On mobile: Mobile Offcanvas Left Drawer Sidebar */
                @media (max-width: 767px) {
                    .main-header .logo-box {
                        display: none !important;
                    }

                    .mobile-header-logo {
                        display: flex !important;
                        align-items: center !important;
                    }

                    .mobile-hamburger-btn {
                        display: flex !important;
                        align-items: center !important;
                    }

                    /* Hide original SmartMenus hamburger label inside main-nav */
                    .main-nav>label.main-menu-btn {
                        display: none !important;
                    }

                    .main-header {
                        height: 60px !important;
                        min-height: 60px !important;
                        max-height: 60px !important;
                        overflow: visible !important;
                    }

                    .main-header .inside-header {
                        height: 60px !important;
                        min-height: 60px !important;
                        max-height: 60px !important;
                        overflow: visible !important;
                    }

                    /* Mobile Offcanvas Left Drawer Sidebar Container */
                    .layout-top-nav.fixed-manu .main-nav,
                    .main-nav {
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        bottom: 0 !important;
                        width: 300px !important;
                        max-width: 85vw !important;
                        height: 100vh !important;
                        height: 100dvh !important;
                        margin-top: 0 !important;
                        z-index: 100001 !important;
                        background-color: #0E0E23 !important;
                        box-shadow: 6px 0 30px rgba(0, 0, 0, 0.45) !important;
                        transform: translateX(-100%) !important;
                        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        display: flex !important;
                        flex-direction: column !important;
                        overflow: hidden !important;
                    }

                    .dark-skin .main-nav {
                        background-color: #24243E !important;
                    }

                    /* Fixed Header at Top of Drawer */
                    .mobile-drawer-header {
                        flex-shrink: 0 !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: space-between !important;
                        padding: 16px 20px !important;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                        background-color: #0E0E23 !important;
                        z-index: 10 !important;
                    }

                    .dark-skin .mobile-drawer-header {
                        background-color: #24243E !important;
                        border-bottom-color: rgba(255, 255, 255, 0.12) !important;
                    }

                    .mobile-drawer-logo img {
                        filter: brightness(0) invert(1) !important;
                    }

                    /* Drawer Close Button (X) */
                    .mobile-drawer-close-btn {
                        width: 36px !important;
                        height: 36px !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        border-radius: 50% !important;
                        background: rgba(255, 255, 255, 0.1) !important;
                        border: 1px solid rgba(255, 255, 255, 0.15) !important;
                        color: #ffffff !important;
                        font-size: 16px !important;
                        cursor: pointer !important;
                        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                        margin: 0 !important;
                    }

                    .dark-skin .mobile-drawer-close-btn {
                        background: rgba(255, 255, 255, 0.12) !important;
                        color: #ffffff !important;
                    }

                    .mobile-drawer-close-btn:hover,
                    .mobile-drawer-close-btn:active {
                        background: rgba(239, 68, 68, 0.25) !important;
                        border-color: rgba(239, 68, 68, 0.4) !important;
                        color: #f87171 !important;
                        transform: rotate(90deg) scale(1.08) !important;
                    }

                    /* Full-height Vertically Scrollable Menu Container */
                    .main-nav #main-menu,
                    .layout-top-nav #main-menu-state:not(:checked)~#main-menu,
                    .layout-top-nav #main-menu-state:checked~#main-menu {
                        flex: 1 1 auto !important;
                        height: 100% !important;
                        max-height: calc(100vh - 68px) !important;
                        max-height: calc(100dvh - 68px) !important;
                        overflow-y: auto !important;
                        -webkit-overflow-scrolling: touch !important;
                        display: block !important;
                        width: 100% !important;
                        margin: 0 !important;
                        padding: 8px 0 40px 0 !important;
                        background-color: transparent !important;
                        box-shadow: none !important;
                    }

                    /* Make all menu items vertical stacked */
                    .main-nav .sm-blue li {
                        float: none !important;
                        display: block !important;
                        width: 100% !important;
                    }

                    .main-nav .sm-blue a {
                        display: flex !important;
                        align-items: center !important;
                        padding: 13px 20px !important;
                        color: #e2e8f0 !important;
                        font-size: 14px !important;
                        border-radius: 0 !important;
                        border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
                        white-space: normal !important;
                    }

                    .main-nav .sm-blue a:hover,
                    .main-nav .sm-blue a:active,
                    .main-nav .sm-blue a.current {
                        background-color: rgba(255, 255, 255, 0.08) !important;
                        color: #ffffff !important;
                    }

                    /* Submenus: Inline vertical list inside drawer */
                    .main-nav .sm-blue ul {
                        position: static !important;
                        top: auto !important;
                        left: auto !important;
                        width: 100% !important;
                        float: none !important;
                        box-shadow: none !important;
                        background-color: rgba(0, 0, 0, 0.25) !important;
                        padding: 4px 0 !important;
                        border-radius: 0 !important;
                    }

                    .main-nav .sm-blue ul a {
                        padding-left: 36px !important;
                        font-size: 13.5px !important;
                        color: #94a3b8 !important;
                    }

                    .main-nav .sm-blue ul ul a {
                        padding-left: 52px !important;
                    }

                    .main-nav .sm-blue ul a:hover,
                    .main-nav .sm-blue ul a.current {
                        color: #3b82f6 !important;
                        background-color: rgba(59, 130, 246, 0.1) !important;
                    }

                    /* Slide In State when checkbox is checked */
                    #main-menu-state:checked~.main-nav,
                    body:has(#main-menu-state:checked) .main-nav {
                        transform: translateX(0) !important;
                    }

                    /* Mobile Backdrop Blur Overlay */
                    .mobile-menu-backdrop {
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        right: 0 !important;
                        bottom: 0 !important;
                        width: 100vw !important;
                        height: 100vh !important;
                        background: rgba(15, 23, 42, 0.65) !important;
                        backdrop-filter: blur(4px) !important;
                        -webkit-backdrop-filter: blur(4px) !important;
                        z-index: 100000 !important;
                        opacity: 0 !important;
                        visibility: hidden !important;
                        transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.35s !important;
                        margin: 0 !important;
                        cursor: pointer !important;
                    }

                    #main-menu-state:checked~.mobile-menu-backdrop,
                    body:has(#main-menu-state:checked) .mobile-menu-backdrop {
                        opacity: 1 !important;
                        visibility: visible !important;
                    }
                }

                @media (min-width: 768px) {

                    .mobile-header-logo,
                    .mobile-hamburger-btn,
                    .mobile-drawer-header,
                    .mobile-menu-backdrop {
                        display: none !important;
                    }
                }
            </style>

            <div class="navbar-custom-menu r-side float-right">
                @php
                    $currentUser = Auth::user();
                    if ($currentUser) {
                        $currentUser->loadMissing(['position', 'department']);
                        $unreadNotifications = $currentUser->unreadNotifications;
                        $unreadCount = $unreadNotifications->count();
                    } else {
                        $unreadNotifications = collect();
                        $unreadCount = 0;
                    }
                @endphp
                <ul class="nav navbar-nav inline-flex items-center">
                    <li class="dropdown notifications-menu inline-flex rounded-md">
                        <label class="switch" style="margin-bottom: 0;">
                            <a class="waves-effect waves-light btn-primary-light svg-bt-icon">
                                <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                                <span class="switch-on"><i class="fas fa-moon"></i></span>
                                <span class="switch-off"><i class="fas fa-sun"></i></span>
                            </a>
                        </label>
                    </li>
                    <li class="dropdown notifications-menu btn-group ">
                        <a id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="btn-primary-light svg-bt-icon hover:text-white hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-3 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            title="Notifications" type="button">
                            <div id="notificationCount"
                                class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full top-[0.7rem] end-[0.7rem] dark:border-gray-900">
                                {{ $unreadCount }}</div>
                            <i class="fas fa-bell"></i>
                            <div class="pulse-wave"></div>
                        </a>

                        <!-- Dropdown menu -->
                        <div id="dropdown"
                            class="dropdown-menu z-10 bg-white divide-y divide-gray-100 rounded-lg shadow! w-max dark:bg-gray-700"
                            style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-245px, 58px); min-width: 450px;">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">
                                <li class="header">
                                    <div class="p-20 border-b">
                                        <div class="flexbox">
                                            <div>
                                                <div class="text-xl mb-0 mt-0">Notifications</div>
                                            </div>
                                            @if ($currentUser && $currentUser->hasRole('super-admin'))
                                                <div>
                                                    <a href="#" class="text-white hover:bg-red-500"
                                                        id="clearAllNotifications">Clear
                                                        All</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <div class="slimScrollDiv"
                                        style="position: relative; overflow: hidden; width: auto; height: 450px;">
                                        <ul class="menu sm-scrol"
                                            style="overflow-y: scroll; width: auto; height: 450px;">
                                            @foreach ($unreadNotifications as $notification)
                                                @php
                                                    $message =
                                                        data_get($notification, 'data.data.message') ??
                                                        (data_get($notification, 'data.message') ??
                                                            (data_get($notification, 'data.title') ?? 'Notification'));
                                                    $url =
                                                        data_get($notification, 'data.data.url') ??
                                                        (data_get($notification, 'data.url') ?? '#');
                                                @endphp
                                                <li class="border-b flex justify-between items-center">
                                                    <a href="{{ $url }}"
                                                        class="p-3 block m-0 overflow-hidden text-base whitespace-nowrap text-ellipsis flex-grow">
                                                        <i class="fas fa-bell text-info"></i>
                                                        {{ $message }}
                                                    </a>
                                                    <button class="mark-as-read mr-2 hover:text-blue-600"
                                                        data-id="{{ $notification->id }}">Mark as Read</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="slimScrollBar"
                                            style="background: rgb(228, 230, 239); width: 4px; position: absolute; top: 0px; opacity: 0.8; display: none; border-radius: 7px; z-index: 99; right: 3px; height: 207.641px;">
                                        </div>
                                        <div class="slimScrollRail"
                                            style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 3px;">
                                        </div>
                                    </div>
                                </li>
                                <li class="footer p-3 text-center border-t">
                                    <button class="mark-as-read-all hover:text-blue-600">Mark as Read All</button>
                                </li>
                            </ul>
                        </div>

                        <!-- Fullscreen -->
                    <li class="inline-flex rounded-md nav-item max-[1199px]:hidden min-[1200px]:inline-flex">
                        <a href="javascript:void(0);" id="fullscreenButton"
                            class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
                            <i class="fas fa-expand"></i>
                        </a>
                    </li>

                    <!-- User Account-->
                    <li class="btn-group d-xl-inline-flex d-none">
                        <a href="#" id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider-2"
                            class="justify-center btn-primary-light hover:text-white svg-bt-icon hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm !px-px !py-px text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button">
                            @if ($currentUser?->avatar)
                                <img src="{{ $currentUser->avatar_url }}" class="!h-11 !w-11 rounded-full object-cover"
                                    alt="" style="width: 44px; height: 44px;">
                            @else
                                <img src="{{ asset('assets') }}/images/sinarmeadow.webp"
                                    class="avatar rounded-full !h-11 !w-11 mt-1" alt="">
                            @endif

                        </a>

                        <!-- Dropdown menu -->
                        <div id="dropdownDivider-2"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-72 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 drop-shadow-lg"
                                aria-labelledby="dropdownDividerButton">
                                <li>
                                    <p
                                        class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <i class="fa fa-user-circle-o me-3 text-xl" aria-hidden="true"> </i>
                                        {{ $currentUser?->name }} -
                                        {{ $currentUser?->position?->position_name ?? '—' }}
                                    </p>
                                </li>
                                <li>
                                    <p
                                        class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <i class="fa fa-briefcase me-3 text-xl" aria-hidden="true"> </i>
                                        Department {{ $currentUser?->department?->department_name ?? '—' }}
                                    </p>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}"
                                        class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i
                                            class="fa fa-cog me-3 text-xl" aria-hidden="true"> </i>
                                        My Profile</a>
                                </li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <li>
                                        <a :href="route('logout')"
                                            onclick="event.preventDefault();
                                                            this.closest('form').submit();"
                                            class="items-center m-0 text-base flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer"><i
                                                class=" fa fa-sign-out  me-3 text-xl"> </i>
                                            {{ __('Log Out') }}
                                        </a>

                                    </li>
                                </form>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
@push('scripts')
    <script>
        function showSuccessMessage(message) {
            Swal.fire({
                title: 'Success!',
                text: message,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk memperbarui jumlah notifikasi
            function updateNotificationCount() {
                fetch('{{ route('notifications.count') }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('notificationCount').textContent = data.count;
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Event listener untuk tombol "Mark as Read"
            document.querySelectorAll('.mark-as-read').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    fetch('{{ route('notifications.markAsRead') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                notification_id: notificationId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('li').remove();
                                updateNotificationCount();
                            } else {
                                alert('Failed to mark notification as read.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

            // Event listener untuk tombol "Mark as Read All"
            document.querySelector('.mark-as-read-all').addEventListener('click', function() {
                fetch('{{ route('notifications.markAllAsRead') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelectorAll('.menu li').forEach(li => li.remove());
                            updateNotificationCount();
                        } else {
                            alert('Failed to mark all notifications as read.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Event listener untuk tombol "Clear All Notifications"
            const clearAllNotificationsButton = document.getElementById('clearAllNotifications');
            if (clearAllNotificationsButton) {
                clearAllNotificationsButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This action will delete all notifications.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete all!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim request untuk menghapus semua notifikasi
                            $.ajax({
                                type: 'DELETE',
                                url: '{{ route('notifications.clear') }}', // Sesuaikan dengan route yang sesuai
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    showSuccessMessage(
                                        'Semua notifikasi telah dihapus.');
                                    // Refresh halaman atau tindakan lain yang diinginkan
                                    window.location
                                        .reload(); // Contoh: reload halaman setelah penghapusan
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete notifications.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Check local storage for dark mode preference
            const darkModeStorage = localStorage.getItem('darkMode');
            const body = document.body;
            const html = document.documentElement;
            const toggleSwitch = document.getElementById('toggle_left_sidebar_skin');

            // Function to set dark mode
            const setDarkMode = (darkModeOn) => {
                body.classList.toggle('dark-skin', darkModeOn);
                body.classList.toggle('light-skin', !darkModeOn);
                body.classList.toggle('dark', darkModeOn);
                html.classList.toggle('dark', darkModeOn);
                if (toggleSwitch) {
                    toggleSwitch.checked = !!darkModeOn;
                }
                localStorage.setItem('darkMode', darkModeOn ? 'enabled' : 'disabled');
            };

            // Initialize dark mode based on stored preference
            if (darkModeStorage === 'enabled') {
                setDarkMode(true);
            } else if (darkModeStorage === 'disabled') {
                setDarkMode(false);
            }

            // Toggle dark mode when toggle button is clicked
            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', () => {
                    setDarkMode(toggleSwitch.checked);
                });
            }
        });



        // Mobile Burger Toggle State Sync
        document.addEventListener('DOMContentLoaded', function() {
            const menuStateCheckbox = document.getElementById('main-menu-state');
            const mobileBtn = document.querySelector('.mobile-hamburger-btn');

            if (mobileBtn) {
                function updateBurgerState() {
                    const isChecked = menuStateCheckbox && menuStateCheckbox.checked;
                    if (isChecked) {
                        mobileBtn.classList.add('is-active');
                    } else {
                        mobileBtn.classList.remove('is-active');
                    }
                }

                if (menuStateCheckbox) {
                    menuStateCheckbox.addEventListener('change', updateBurgerState);
                }

                mobileBtn.addEventListener('click', function() {
                    setTimeout(updateBurgerState, 50);
                });

                updateBurgerState();
            }
        });
    </script>
@endpush
