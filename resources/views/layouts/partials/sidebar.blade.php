<label for="main-menu-state" class="mobile-menu-backdrop"></label>
<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox">
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Mobile Drawer Top Header (Logo + Close Button X) -->
    <div class="mobile-drawer-header">
        <a href="{{ route('dashboard') }}" class="mobile-drawer-logo">
            <img src="{{ asset('assets/images/logoblack1.webp') }}?v={{ file_exists(public_path('assets/images/logoblack1.webp')) ? filemtime(public_path('assets/images/logoblack1.webp')) : time() }}"
                alt="logo" style="max-width: 140px; height: auto;">
        </a>
        <label class="mobile-drawer-close-btn" for="main-menu-state" title="Close Menu">
            <i class="fas fa-times"></i>
        </label>
    </div>

    <!-- Main Navigation Menu -->
    <ul id="main-menu" class="sm sm-blue">
        @can('view dashboard')
            <li class="{{ request()->is('dashboard', 'dashboard/*') ? 'current' : '' }}">
                <a href="{{ route('dashboard') }}" style="font-size: 18px;">
                    <i data-feather="home" style="width: 18px; height: 18px;">
                        <span class="path1"></span><span class="path2"></span>
                    </i>Dashboard
                </a>
                <ul>
                    @can('view production dashboard')
                        <li>
                            <a href="{{ route('dashboard.dashboardProduction') }}"
                                class="{{ request()->is('dashboard/dashboard-production') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Dashboard Production
                            </a>
                        </li>
                    @endcan
                    @can('view sales dashboard')
                        <li>
                            <a href="{{ route('dashboard.dashboardSales') }}"
                                class="{{ request()->is('dashboard-sales') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Dashboard Sales
                            </a>
                        </li>
                    @endcan
                    @can('view warehouse dashboard')
                        <li>
                            <a href="{{ route('dashboard.dashboardWarehouse') }}"
                                class="{{ request()->is('dashboard-warehouse') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Dashboard Warehouse
                            </a>
                        </li>
                    @endcan
                    @can('view safety board dashboard')
                        <li>
                            <a href="{{ route('dashboard.safety-board.index') }}"
                                class="{{ request()->is('dashboard/safety-board*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Dashboard Safety Board
                            </a>
                        </li>
                    @endcan
                    @can('view kanban dashboard')
                        <li>
                            <a href="{{ route('kanban.jobs.index') }}" class="{{ request()->is('kanban/jobs*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Dashboard Kanban
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view user management')
            <li
                class="{{ request()->is('users*', 'departments*', 'positions*', 'levels*', 'roles*', 'permissions*', 'get-data-master*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="users" style="width: 18px; height: 18px;"></i>
                    User Management
                </a>
                <ul>
                    @can('view user')
                        <li>
                            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Users
                            </a>
                        </li>
                    @endcan
                    @can('view department')
                        <li>
                            <a href="{{ route('department.index') }}" class="{{ request()->is('departments*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Departments
                            </a>
                        </li>
                    @endcan
                    @can('view position')
                        <li>
                            <a href="{{ route('position.index') }}" class="{{ request()->is('positions*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Positions
                            </a>
                        </li>
                    @endcan
                    @can('view level')
                        <li>
                            <a href="{{ route('level.index') }}" class="{{ request()->is('levels*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Levels
                            </a>
                        </li>
                    @endcan
                    @can('view role')
                        <li>
                            <a href="{{ route('roles.index') }}" class="{{ request()->is('roles*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Roles
                            </a>
                        </li>
                    @endcan
                    @can('view permission')
                        <li>
                            <a href="{{ route('permissions.index') }}" class="{{ request()->is('permissions*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Permission
                            </a>
                        </li>
                    @endcan
                    @can('get master data')
                        <li>
                            <a href="{{ route('get.master') }}" class="{{ request()->is('get-data-master*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Get Data Master
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view data dashboard')
            <li class="{{ request()->is('dashboard/inventory*', 'production*', 'sales*', 'dashboard/standard-production*', 'dashboard/standard-warehouse*', 'dashboard/standard-shipment*', 'standard-budgets*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="database" style="width: 18px; height: 18px;"></i>
                    Data Dashboard
                </a>
                <ul>
                    @can('view inventory dashboard')
                        <li>
                            <a href="{{ route('dashboard.inventory') }}" class="{{ request()->is('dashboard/inventory*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Inventory Dashboard
                            </a>
                        </li>
                    @endcan
                    @can('view production dashboard')
                        <li>
                            <a href="{{ route('data.production') }}" class="{{ request()->is('production*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Data Production
                            </a>
                        </li>
                    @endcan
                    @can('view standard production dashboard')
                        <li>
                            <a href="{{ route('dashboard.production.standard') }}" class="{{ request()->is('dashboard/standard-production*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Standard Production
                            </a>
                        </li>
                    @endcan
                    @can('view standard warehouse dashboard')
                        <li>
                            <a href="{{ route('dashboard.warehouseindex') }}" class="{{ request()->is('dashboard/standard-warehouse*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Standard Warehouse
                            </a>
                        </li>
                    @endcan
                    @can('view standard shipment dashboard')
                        <li>
                            <a href="{{ route('data.sales') }}" class="{{ request()->is('sales*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Data Sales
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.shipmentindex') }}" class="{{ request()->is('dashboard/standard-shipment*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Standard Shipment
                            </a>
                        </li>
                    @endcan
                    @can('view standard budget dashboard')
                        <li>
                            <a href="{{ route('standard-budgets.index') }}" class="{{ request()->is('standard-budgets*') ? 'current' : '' }}">
                                <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                Standard Budgets
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('view kanban')
            <li
                class="{{ request()->is('kanban*') ? 'current' : '' }}">
                <a href="#" style="font-size: 18px;">
                    <i data-feather="briefcase" style="width: 18px; height: 18px;"></i>
                    Kanban
                </a>
                <ul>
                    <li>
                        <a href="{{ route('kanban.jobs.index') }}" class="{{ request()->is('kanban/jobs*') ? 'current' : '' }}">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            Kanban Board
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kanban.areas.index') }}" class="{{ request()->is('kanban/areas*') ? 'current' : '' }}">
                            <i class="icon-Map-pin"><span class="path1"></span><span class="path2"></span></i>
                            Manage Areas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kanban.departments.index') }}"
                            class="{{ request()->is('kanban/departments*') ? 'current' : '' }}">
                            <i class="icon-Users"><span class="path1"></span><span class="path2"></span></i>
                            Manage Departments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kanban.users.index') }}"
                            class="{{ request()->is('kanban/users*') ? 'current' : '' }}">
                            <i class="icon-Users"><span class="path1"></span><span class="path2"></span></i>
                            Manage Kanban Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kanban.activity-logs.index') }}"
                            class="{{ request()->is('kanban/activity-logs*') ? 'current' : '' }}">
                            <i class="icon-History"><span class="path1"></span><span class="path2"></span></i>
                            Activity Logs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kanban.reports.jobs.page') }}"
                            class="{{ request()->is('kanban/reports*') ? 'current' : '' }}">
                            <i class="icon-File_Export"><span class="path1"></span><span class="path2"></span></i>
                            Kanban Jobs Export
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
    </ul>
</nav>
