<label for="main-menu-state" class="mobile-menu-backdrop"></label>
<nav class="main-nav" role="navigation">

  <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox">
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Mobile Drawer Top Header (Logo + Close Button X) -->
    <div class="mobile-drawer-header">
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="mobile-drawer-logo">
            <img src="{{ asset('assets/images/logoblack1.webp') }}?v={{ filemtime(public_path('assets/images/logoblack1.webp')) }}"
                alt="logo" style="max-width: 140px; height: auto;">
        </a>
        <label class="mobile-drawer-close-btn" for="main-menu-state" title="Close Menu">
            <i class="fas fa-times"></i>
        </label>
    </div>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        @can('view dashboard')
            <li class="{{ request()->is('dashboard/*') ? 'current' : '' }}"><a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}"
                    style="font-size: 18px;"><i data-feather="home" style="width: 18px; height: 18px;"><span
                            class="path1"></span><span class="path2"></span></i>Dashboard</a>
                <ul>
                    @can('view production dashboard')
                        <li><a href="{{ Route::has('dashboard.dashboardProduction') ? route('dashboard.dashboardProduction') : '#' }}"
                                class="{{ request()->is('dashboard/dashboard-production') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Production</a></li>
                    @endcan
                    @can('view sales dashboard')
                        <li><a href="{{ Route::has('dashboard.dashboardSales') ? route('dashboard.dashboardSales') : '#' }}"
                                class="{{ request()->is('dashboard/dashboard-sales') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Sales</a></li>
                    @endcan
                    @can('view warehouse dashboard')
                        <li><a href="{{ Route::has('dashboard.dashboardWarehouse') ? route('dashboard.dashboardWarehouse') : '#' }}"
                                class="{{ request()->is('dashboard/dashboard-warehouse') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Warehouse</a></li>
                    @endcan
                    @can('view safety board dashboard')
                        <li><a href="{{ Route::has('dashboard.safety-board.index') ? route('dashboard.safety-board.index') : '#' }}"
                                class="{{ request()->is('dashboard/safety-board') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Safety
                                Board</a></li>
                    @endcan
                    @can('view ecommerce dashboard')
                        <li><a href="{{ Route::has('dashboard.ecommerce') ? route('dashboard.ecommerce') : '#' }}"
                                class="{{ request()->is('/dashboard/ecommerce') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Ecommerce</a></li>
                    @endcan
                    @can('view marsho dashboard')
                        <li><a href="{{ Route::has('production.monitoring.index') ? route('production.monitoring.index') : '#' }}"
                                class="{{ request()->is('/production-monitoring') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Marsho Line Status</a></li>
                    @endcan
                    @can('view inward dashboard')
                        <li><a href="{{ Route::has('inward.dashboard') ? route('inward.dashboard') : '#' }}"
                                class="{{ request()->is('/inward-dashboard') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Dashboard
                                Inward</a></li>
                    
                    @endcan
                    @can('view oil monitoring dashboard')
                        <li><a href="{{ Route::has('oil.index') ? route('oil.index') : '#' }}"
                                class="{{ request()->is('/oil-monitoring') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Dashboard
                                Oil Monitoring</a></li>
                    @endcan
                    @can('view oil stock dashboard')
                        <li><a href="{{ Route::has('rbd.dashboard') ? route('rbd.dashboard') : '#' }}"
                                class="{{ request()->is('/oil//inventoryoil-dashboard') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Dashboard
                                Oil Stock</a></li>
                    @endcan
                    <li><a href="{{ Route::has('dashboard.ppic') ? route('dashboard.ppic') : '#' }}"
                            class="{{ request()->is('dashboard/ppic*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                    class="path1"></span><span class="path2"></span></i>Dashboard PPIC</a></li>

                    @can('view kanban marsho dashboard')
                        <li><a href="{{ Route::has('jobs.index') ? route('jobs.index') : '#' }}" class="{{ request()->is('/jobs') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                                Kanban Marsho</a></li>
                    @endcan
                    @can('view custom media viewer dashboard')
                    <li><a href="{{ Route::has('dashboard.custom-view') ? route('dashboard.custom-view') : '#' }}"
                            class="{{ request()->is('dashboard/custom-view*') ? 'current' : '' }}"><i
                                class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dashboard
                            Media Viewer</a></li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('view user management')
            <li
                class="{{ request()->is('users*') || request()->is('department*') || request()->is('position*') || request()->is('level*') || request()->is('roles*') || request()->is('permissions*') || request()->is('get.master*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="users" style="width: 18px; height: 18px;"></i>
                    User Management
                </a>
                <ul>
                    @can('view user')
                        <li><a href="{{ Route::has('users.index') ? route('users.index') : '#' }}" class="{{ request()->is('users*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Users</a>
                        </li>
                    @endcan
                    @can('view department')
                        <li><a href="{{ Route::has('department.index') ? route('department.index') : '#' }}"
                                class="{{ request()->is('department*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Departments</a></li>
                    @endcan
                    @can('view position')
                        <li><a href="{{ Route::has('position.index') ? route('position.index') : '#' }}"
                                class="{{ request()->is('position*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Positions</a></li>
                    @endcan
                    @can('view level')
                        <li><a href="{{ Route::has('level.index') ? route('level.index') : '#' }}" class="{{ request()->is('level*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Levels</a>
                        </li>
                    @endcan
                    @can('view role')
                        <li><a href="{{ Route::has('roles.index') ? route('roles.index') : '#' }}" class="{{ request()->is('roles*') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Roles</a>
                        </li>
                    @endcan
                    @can('view permission')
                        <li><a href="{{ Route::has('permissions.index') ? route('permissions.index') : '#' }}"
                                class="{{ request()->is('permissions*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Permission</a></li>
                    @endcan
                    {{-- @can('get master data') --}}
                    <li><a href="{{ Route::has('get.master') ? route('get.master') : '#' }}" class="{{ request()->is('get.master*') ? 'current' : '' }}"><i
                                class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Get Data
                            Master </a></li>
                    {{-- @endcan --}}
                  
                </ul>
            </li>
        @endcan

        @can('view data dashboard')
            <li><a href="#" style="font-size: 18px;"
                    class="{{ request()->is(['dashboard/inventory', 'dashboard/standard-production', 'dashboard/standard-warehouse']) ? 'current' : '' }}"><i
                        data-feather="database" style="width: 18px; height: 18px;"></i>Data Dashboard</a>
                <ul>
                    {{-- @can('view sales dashboard')
                <li><a href="{{ Route::has('dashboard.sales') ? route('dashboard.sales') : '#' }}"
                        class="{{ request()->is('dashboard/sales') ? 'current' : '' }}"><i class="icon-Commit"><span
                                class="path1"></span><span class="path2"></span></i>Sales Dashboard </a></li>
                @endcan --}}
                    @can('view inventory dashboard')
                        <li><a href="{{ Route::has('dashboard.inventory') ? route('dashboard.inventory') : '#' }}"
                                class="{{ request()->is('dashboard/inventory') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Inventory
                                Dashboard</a></li>
                    @endcan
                    @can('view production dashboard')
                        <li><a href="{{ Route::has('data.production') ? route('data.production') : '#' }}"
                                class="{{ request()->is('data.production') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Data Production
                            </a></li>
                        <li><a href="{{ Route::has('production.summary') ? route('production.summary') : '#' }}"
                                class="{{ request()->is('/production/summary') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Production
                                Summary by Line
                            </a></li>
                        <li>
                            <a href="{{ Route::has('reports.sales.byBrand') ? route('reports.sales.byBrand') : '#' }}"
                                class="{{ request()->routeIs('reports.sales.byBrand') ? 'current' : '' }}">

                                <i class="icon-tags"></i> {{-- Example Icon --}}
                                <span>Sales By Brand</span>

                            </a>
                        </li>
                    @endcan
                    @can('view standard production dashboard')
                        <li><a href="{{ Route::has('dashboard.production.standard') ? route('dashboard.production.standard') : '#' }}"
                                class="{{ request()->is('dashboard/standard-production/') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                                Production</a></li>
                    @endcan
                    @can('view standard warehouse dashboard')
                        <li><a href="{{ Route::has('dashboard.warehouseindex') ? route('dashboard.warehouseindex') : '#' }}"
                                class="{{ request()->is('dashboard/standard-warehouse') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                                Warehouse</a></li>
                    @endcan
                    @can('view standard shipment dashboard')
                        <li><a href="{{ Route::has('data.sales') ? route('data.sales') : '#' }}" class="{{ request()->is('sales') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Data
                                Sales</a></li>
                    @endcan

                    @can('view standard budget dashboard')
                        <li><a href="{{ Route::has('standard-budgets.index') ? route('standard-budgets.index') : '#' }}"
                                class="{{ request()->is('standard-budgets.index') ? 'current' : '' }}">

                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Standard Budgets</a></li>
                    @endcan
                      @can('view inward dashboard')
                        <li><a href="{{ Route::has('standard.warehouse-inward.index') ? route('standard.warehouse-inward.index') : '#' }}"
                                class="{{ request()->is('/standard-warehouse-inward*') ? 'current' : '' }}"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Standard
                                Inward</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view sales performance')
            <li>
                <a href="#" style="font-size: 18px;"
                    class="{{ request()->is('sales-performance*') ? 'current' : '' }}"><i data-feather="bar-chart"
                        style="width: 18px; height: 18px;"></i> Sales Performance
                </a>
                <ul>
                    @can('view dashboard sales performance')
                        <li><a href="{{ Route::has('chart.dashboard') ? route('chart.dashboard') : '#' }}" style="font-size: 14px;"
                                class="{{ request()->is('sales-performance/chart-dashboard') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                {{-- <i data-feather="credit-card" style="width: 14px; height: 14px;"></i> --}}
                                Sales Performance Dashboard
                            </a>
                        </li>

                        <li><a href="{{ Route::has('sales.performance.index') ? route('sales.performance.index') : '#' }}" style="font-size: 14px;"
                                class="{{ request()->is('sales-performance') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                {{-- <i data-feather="credit-card" style="width: 14px; height: 14px;"></i> --}}
                                Sales Performance
                            </a>
                        </li>

                        <li><a href="{{ Route::has('standard.sales.performance') ? route('standard.sales.performance') : '#' }}"
                                class="{{ request()->is('sales-performance/standard-sales-performance') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Standard
                                Sales Performance</a>
                        </li>
                    @endcan
                    @can('view retail performance')
                        <li class="{{ request()->is('ap-retail*') }}">
                            <a href="#"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Retail Performances</a>
                            <ul>
                                <li class="{{ request()->is('ap-retail-dashboard') ? 'current' : '' }}"><a
                                        href="{{ Route::has('ap.retail') ? route('ap.retail') : '#' }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Retail Dashboard</a>
                                </li>
                                @can('view retail sales performance')
                                <li class="{{ request()->is('ap-retail') ? 'current' : '' }}"><a
                                        href="{{ Route::has('ap.sales.performance') ? route('ap.sales.performance') : '#' }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Retail Sales
                                        Performance</a>
                                </li>
                                @endcan
                                 @can('view retail standard performance')
                                <li class="{{ request()->is('ap-retail/standard-tonnage') ? 'current' : '' }}"><a
                                        href="{{ Route::has('standard.tonnage.data') ? route('standard.tonnage.data') : '#' }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Standard Tonnage</a>
                                </li>
                                @endcan

                            </ul>
                        </li>
                    @endcan
                    @can('view sales report performance')
                        <li><a href="{{ Route::has('sales.performance.detail.report') ? route('sales.performance.detail.report') : '#' }}"
                                class="{{ request()->is('sales-performance/detailed-reports') ? 'current' : '' }}"><i
                                    class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Sales
                                Report </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view hse form')
            <li class="{{ request()->is('accidents-report*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="shield" style="width: 18px; height: 18px;"></i>
                    Accident Reports
                </a>
                <ul>
                    <li>
                        <a href="{{ Route::has('accidents-report.index') ? route('accidents-report.index') : '#' }}" class="{{ request()->is('accidents-report') ? 'current' : '' }}">
                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                            All Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('accidents-report.my-approvals') ? route('accidents-report.my-approvals') : '#' }}" class="{{ request()->is('accidents-report/my-approvals') ? 'current' : '' }}">
                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                            My Approvals
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('view ecommerce')
            <li class="{{ request()->is('/ecommerce*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    {{-- Mengganti ikon menjadi keranjang belanja --}}
                    <i data-feather="shopping-cart" style="width: 18px; height: 18px;"></i>
                    E-Commerce
                </a>
                <ul>
                    {{-- <li>
                    Mengarahkan link ke route 'dashboard.ecommerce'
                    <a href="{{ Route::has('dashboard.ecommerce') ? route('dashboard.ecommerce') : '#' }}"
                        class="{{ request()->is('/ecommerce*') ? 'current' : '' }}">
                        Anda bisa mengganti ikon ini sesuai dengan set ikon yang Anda gunakan
                        <i class="icon-Dashboard"><span class="path1"></span><span class="path2"></span></i>
                        Dashboard
                    </a>
                </li> --}}
                    <li>
                        <a href="{{ Route::has('ecommerce.products.index') ? route('ecommerce.products.index') : '#' }}"
                            class="{{ request()->is('/ecommerce/products*') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('ecommerce.sales.index') ? route('ecommerce.sales.index') : '#' }}"
                            class="{{ request()->is('/ecommerce/sales*') ? 'current' : '' }}">
                            {{-- Ganti 'icon-Chart-line' jika perlu --}}
                            <i class="icon-Chart-line"></i>
                            Sales
                        </a>
                    </li>

                    {{-- (TAMBAHAN) Link Navigasi Konfigurasi --}}
                    <li>
                        <a href="{{ Route::has('ecommerce.settings.index') ? route('ecommerce.settings.index') : '#' }}"
                            class="{{ request()->is('/ecommerce/settings*') ? 'current' : '' }}">
                            {{-- Ganti 'icon-Gear' jika perlu --}}
                            <i class="icon-Gear"></i>
                            Configuration
                        </a>
                    </li>

                    <li>
                        <a href="{{ Route::has('ecommerce.products.tonnage.index') ? route('ecommerce.products.tonnage.index') : '#' }}"
                            class="{{ request()->is('ecommerce/products/tonnage*') ? 'current' : '' }}">
                            {{-- Ganti 'icon-Gear' jika perlu --}}
                            <i class="icon-Gear"></i>
                            Tonase
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @can('view marsho')
            <li class="{{ request()->is('/production-monitoring*') ? 'current' : '' }}">
                {{-- <a href="#" style="font-size: 18px;">
                <i data-feather="shopping-cart" style="width: 18px; height: 18px;"></i>
                Marsho Line Status
            </a> --}}
                <ul>
                    {{-- <li>
                    <a href="{{ Route::has('ecommerce.products.index') ? route('ecommerce.products.index') : '#' }}"
                        class="{{ request()->is('/ecommerce/products*') ? 'current' : '' }}">
                        <i class="icon-Box"></i>
                        Products
                    </a>
                </li>
                <li>
                    <a href="{{ Route::has('ecommerce.sales.index') ? route('ecommerce.sales.index') : '#' }}"
                        class="{{ request()->is('/ecommerce/sales*') ? 'current' : '' }}">
                        <i class="icon-Chart-line"></i>
                        Sales
                    </a>
                </li> --}}

                    {{-- (TAMBAHAN) Link Navigasi Konfigurasi --}}
                    {{-- <li>
                    <a href="{{ Route::has('ecommerce.settings.index') ? route('ecommerce.settings.index') : '#' }}"
                        class="{{ request()->is('/ecommerce/settings*') ? 'current' : '' }}">
                        <i class="icon-Gear"></i>
                        Configuration
                    </a>
                </li> --}}

                    {{-- <li>
                    <a href="{{ Route::has('ecommerce.products.tonnage.index') ? route('ecommerce.products.tonnage.index') : '#' }}"
                        class="{{ request()->is('ecommerce/products/tonnage*') ? 'current' : '' }}">
                        <i class="icon-Gear"></i>
                        Tonase
                    </a>
                </li> --}}
                </ul>
            </li>
        @endcan
        @can('view kanban marsho')
            <li
                class="{{ request()->is(['jobs*', 'areas*', 'marsho-departments*', 'marsho-users*', 'activity-logs*', 'reports/marsho-jobs*']) ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="briefcase" style="width: 18px; height: 18px;"></i> Marsho JobBoard
                </a>
                <ul>
                    <li><a href="{{ Route::has('jobs.index') ? route('jobs.index') : '#' }}" class="{{ request()->is('jobs*') ? 'current' : '' }}"><i
                                class="icon-Layout-4-blocks"><span class="path1"></span><span
                                    class="path2"></span></i>Jobs Kanban</a></li>
                    <li><a href="{{ Route::has('areas.index') ? route('areas.index') : '#' }}" class="{{ request()->is('areas*') ? 'current' : '' }}"><i
                                class="icon-Map-pin"><span class="path1"></span><span class="path2"></span></i>Manage
                            Areas</a></li>
                    <li><a href="{{ Route::has('marsho-departments.index') ? route('marsho-departments.index') : '#' }}"
                            class="{{ request()->is('marsho-departments*') ? 'current' : '' }}"><i
                                class="icon-Users"><span class="path1"></span><span class="path2"></span></i>Manage
                            Departments</a></li>
                    <li><a href="{{ Route::has('marsho-users.index') ? route('marsho-users.index') : '#' }}"
                            class="{{ request()->is('marsho-users*') ? 'current' : '' }}"><i class="icon-Users"><span
                                    class="path1"></span><span class="path2"></span></i>Manage Marsho Users</a></li>
                    <li><a href="{{ Route::has('activity-logs.index') ? route('activity-logs.index') : '#' }}"
                            class="{{ request()->is('activity-logs*') ? 'current' : '' }}"><i class="icon-History"><span
                                    class="path1"></span><span class="path2"></span></i>Activity Logs</a></li>
                    <li><a href="{{ Route::has('reports.marsho-jobs.page') ? route('reports.marsho-jobs.page') : '#' }}"
                            class="{{ request()->is('reports/marsho-jobs*') ? 'current' : '' }}"><i
                                class="icon-File_Export"><span class="path1"></span><span
                                    class="path2"></span></i>Marsho Jobs Export</a></li>
                </ul>
            </li>
        @endcan
        @can('view environment')
            <li class="{{ request()->is('environment*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="globe" style="width: 18px; height: 18px;"></i>
                    HSE Environment
                </a>
                <ul>
                    <li>
                        <a href="{{ Route::has('env.master-data') ? route('env.master-data') : '#' }}"
                            class="{{ request()->is('environment/master-data') ? 'current' : '' }}">
                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                            Master Data
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('env.dashboard') ? route('env.dashboard') : '#' }}"
                            class="{{ request()->is('environment/dashboard') ? 'current' : '' }}">
                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('env.meter.form') ? route('env.meter.form') : '#' }}"
                            class="{{ request()->is('environment/meter/form*') ? 'current' : '' }}">
                            <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                            Input Data Meteran
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('view oil monitoring')
            <li class="{{ request()->is(['oil/oil-input', 'oil/oil-config']) ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="monitor" style="width: 18px; height: 18px;"></i>
                    OIL Monitoring
                </a>
                <ul>
                    <li>
                        <a href="{{ Route::has('oil.input_station.index') ? route('oil.input_station.index') : '#' }}"
                            class="{{ request()->is('/oil-input') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Oil Monitoring Input
                        </a>
                    </li>
                    @can('view oil config monitoring')
                        <li>
                            <a href="{{ Route::has('oil.config.center') ? route('oil.config.center') : '#' }}"
                                class="{{ request()->is('/oil-config') ? 'current' : '' }}">
                                <i class="icon-Box"></i>
                                Oil Monitoring Config Center
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('view oil stock')
            <li class="{{ request()->is('oil/inventoryoil-dashboard', 'oil/inventoryoil/*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="droplet" style="width: 18px; height: 18px;"></i>
                    OIL Stock
                </a>
                <ul>
                    <li>
                        <a href="{{ Route::has('rbd.dashboard') ? route('rbd.dashboard') : '#' }}"
                            class="{{ request()->is('oil/inventoryoil-dashboard') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Inventory Oil Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('oil.inventoryoil.master') ? route('oil.inventoryoil.master') : '#' }}"
                            class="{{ request()->is('oil/inventoryoil/master') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Oil Stock Master
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('oil.inventoryoil.tank') ? route('oil.inventoryoil.tank') : '#' }}"
                            class="{{ request()->is('oil/inventoryoil/tank') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Oil Tank Master
                        </a>
                    </li>
                    <li>
                        <a href="{{ Route::has('oil.inventoryoil.inout') ? route('oil.inventoryoil.inout') : '#' }}"
                            class="{{ request()->is('oil/inventoryoil/inout') ? 'current' : '' }}">
                            <i class="icon-Box"></i>
                            Oil Stock In Out History
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

    </ul>
</nav>

