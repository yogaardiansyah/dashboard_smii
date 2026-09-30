<footer class="main-footer" style="background-color: #0E0E23; color: #ffffff; width: 100% !important; margin: 0 !important; border-top: 1px solid rgba(255, 255, 255, 0.08); padding: 1.25rem 0;">
    <div class="inside-footer" style="width: 95%; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div class="text-sm" style="color: rgba(255, 255, 255, 0.85);">
            &copy; {{ date('Y') }} <span style="font-weight: 600; color: #ffffff;">Operational Dashboard</span> | <span style="font-weight: 600; color: #ffffff;">SINARMEADOW</span>. All Rights Reserved.
        </div>
        <div class="max-[575px]:hidden min-[576px]:inline-flex items-center">
            <ul class="nav nav-primary nav-dotted nav-dot-separated justify-center justify-content-md-end mb-0" style="list-style: none; padding: 0; margin: 0;">
                @if (request()->is('dashboard/dashboard-production'))
                    <li class="nav-item">
                        <div class="flex items-center">
                            <p class="nav-link text-bold mb-0 p-0" style="color: #f1a707; font-weight: 600;">In Collaboration with</p>
                            <img src="{{ asset('assets/images/logo/binus.png') }}" alt="BINUS" class="w-50 h-30 ml-2">
                        </div>
                    </li>
                @elseif (request()->is('dashboard/safety-board'))
                    <li class="nav-item">
                        <div class="flex items-center">
                            <p class="nav-link text-bold mb-0 p-0" style="color: #f1a707; font-weight: 600;">In Collaboration with</p>
                            <img src="{{ asset('assets/images/logo/gundar.png') }}" alt="GUNDAR" class="w-50 h-40 ml-2">
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" style="color: #f1a707; font-weight: 600;" href="https://sinarmeadow.com"
                            target="_blank">PT . SINAR MEADOW OFFICIAL</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</footer>

<style>
    .layout-top-nav .main-footer {
        width: 100% !important;
        margin: 0 !important;
        background-color: #0E0E23 !important;
        color: #ffffff !important;
    }
    .dark-skin .main-footer {
        background-color: #12213c !important;
    }
</style>

