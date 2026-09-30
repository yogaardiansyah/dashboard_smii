<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSalesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HSE\SafetyBoardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\Marsho\ActivityLogController;
use App\Http\Controllers\Marsho\AreaController;
use App\Http\Controllers\Marsho\JobController;
use App\Http\Controllers\Marsho\MarshoDepartmentController;
use App\Http\Controllers\Marsho\MarshoUserController;
use App\Http\Controllers\Marsho\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QAD\InventoryController;
use App\Http\Controllers\QAD\ProductionController;
use App\Http\Controllers\QAD\SalesController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\StandardBudgetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Kanban\JobController as KanbanJobController;
use App\Http\Controllers\Kanban\AreaController as KanbanAreaController;
use App\Http\Controllers\Kanban\DepartmentController as KanbanDepartmentController;
use App\Http\Controllers\Kanban\UserController as KanbanUserController;
use App\Http\Controllers\Kanban\ActivityLogController as KanbanActivityLogController;
use App\Http\Controllers\Kanban\ReportController as KanbanReportController;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    SEOMeta::setTitle('Intra Dashboard SMII');

    return redirect()->route('login');
});

Route::middleware('auth', 'redirect.if.role')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-finance', [DashboardController::class, 'index-finance'])->name('dashboard-finance');
    Route::get('/api/requisitions/{year}', [DashboardController::class, 'getRequisitionsByYear'])->name('dashboard.requisitions.byYear');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.updates');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/get-data-master', [UserController::class, 'getDataMaster'])->name('get.master');

    Route::get('/dashboard-sales', [DashboardSalesController::class, 'showMapDashboard'])->name('dashboard.dashboardSales');
    Route::get('/api/sales-data', [DashboardSalesController::class, 'getSalesData'])->name('api.sales.data');

    Route::get('dashboard/inventory/wsa', [InventoryController::class, 'getDashboardInventory'])->name('dashboard.inventory.wsa');
    Route::get('dashboard/inventory', [InventoryController::class, 'index'])->name('dashboard.inventory');

    Route::post('dashboard/production/get', [ProductionController::class, 'getProductions'])->name('dashboard.production.wsa');
    Route::get('production', [ProductionController::class, 'index'])->name('data.production');

    Route::post('dashboard/sales/get', [SalesController::class, 'getSalesDashboard'])->name('dashboard.sales.wsa');
    Route::get('sales', [SalesController::class, 'index'])->name('data.sales');

    Route::prefix('standard-budgets')->name('standard-budgets.')->middleware('auth')->group(function () { // Added middleware('auth') as an example, adjust as needed
        Route::get('/', [StandardBudgetController::class, 'index'])->name('index');
        Route::post('/', [StandardBudgetController::class, 'store'])->name('store');

        Route::get('/download-sample', [StandardBudgetController::class, 'downloadSample'])->name('download-sample');
        Route::post('/import', [StandardBudgetController::class, 'importExcel'])->name('import.excel');

        // **IMPORTANT: Define specific routes like bulk-delete BEFORE parameterized routes**
        Route::delete('/bulk-delete', [StandardBudgetController::class, 'bulkDestroy'])->name('bulk-delete'); // MOVED UP

        // CRUD for individual items (parameterized routes)
        Route::get('/{standardBudget}/edit', [StandardBudgetController::class, 'edit'])->name('edit');
        Route::put('/{standardBudget}', [StandardBudgetController::class, 'update'])->name('update');
        Route::delete('/{standardBudget}', [StandardBudgetController::class, 'destroy'])->name('destroy'); // Stays after bulk-delete
    });

    Route::get('/standard-budgets/sample/download', function () {
        $filePath = storage_path('app/files/sample_budgets.xlsx');

        if (! file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, 'sample_budgets.xlsx');
    })->name('standard-budgets.download-sample');

    Route::get('dashboard/standard-production/', [ProductionController::class, 'standardProduction'])->name('dashboard.production.standard');
    Route::post('dashboard/standard-production/', [ProductionController::class, 'storeStandardProductions'])->name('dashboard.production.standard.store');
    Route::put('dashboard/standard-production/update/{standardproduction}', [ProductionController::class, 'updateStandardProductions'])->name('dashboard.standard-production.update');
    Route::delete('dashboard/standard-production/destroy/{standardproduction}', [ProductionController::class, 'destroyStandardProductions'])->name('dashboard.standard-production.destroy');

    Route::post('dashboard/standard-shipment', [SalesController::class, 'getShipment'])->name('dashboard.standardshipment.wsa');

    Route::get('dashboard/standard-shipment', [SalesController::class, 'shipmentindex'])->name('dashboard.shipmentindex');
    Route::post('dashboard/standard-shipment/store', [SalesController::class, 'shipmentstore'])->name('dashboard.shipmentstore');
    Route::put('dashboard/standard-shipment/{standardshipment}', [SalesController::class, 'shipmentupdate'])->name('dashboard.shipmentupdate');
    Route::delete('dashboard/standard-shipment/{standardshipment}', [SalesController::class, 'shipmentdelete'])->name('dashboard.shipmentdelete');

    Route::get('dashboard/standard-warehouse', [InventoryController::class, 'warehouseindex'])->name('dashboard.warehouseindex');
    Route::post('dashboard/standard-warehouse', [InventoryController::class, 'warehousestore'])->name('dashboard.warehousestore');
    Route::put('dashboard/standard-warehouse/{standardwarehouse}', [InventoryController::class, 'warehouseupdate'])->name('dashboard.warehouseupdate');
    Route::delete('dashboard/standard-warehouse/{standardwarehouse}', [InventoryController::class, 'warehousedelete'])->name('dashboard.warehousedelete');

    Route::post('dashboard/inventory/wsa', [InventoryController::class, 'getDashboardInventory'])->name('dashboard.inventory.wsa');
    Route::get('dashboard/inventory', [InventoryController::class, 'index'])->name('dashboard.inventory');

    Route::get('dashboard-warehouse', function () {
        return view('dashboard.dashboardWarehouse');
    })->name('dashboard.dashboardWarehouse');
    Route::get('dashboard/dashboard-production', [ProductionController::class, 'dashboardProductionIndex'])->name('dashboard.dashboardProduction');

    Route::get('dashboard-inventory', [InventoryController::class, 'dashboardInventory'])->name('dashboard.dashboardInventory');

    Route::prefix('dashboard/safety-board')->name('dashboard.safety-board.')->group(function () {
        Route::get('/', [SafetyBoardController::class, 'index'])->name('index');
        Route::get('/api/safety-data', [SafetyBoardController::class, 'getSafetyData']); // For AJAX calls

    });

    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/clear', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    Route::get('/notifications/count', function () {
        return response()->json(['count' => auth()->user()->unreadNotifications->count()]);
    })->name('notifications.count');

    // Marsho Job Board
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::post('/jobs/{job}/forward', [JobController::class, 'forward'])->name('jobs.forward');
    Route::patch('/jobs/{job}/complete', [JobController::class, 'complete'])->name('jobs.complete');
    Route::post('/jobs/{job}/close', [JobController::class, 'close'])->name('jobs.close');
    Route::patch('/jobs/{job}/change-status', [JobController::class, 'changeStatus'])->name('jobs.changeStatus');
    Route::get('/jobs/{job}/details', [JobController::class, 'showDetails'])->name('jobs.details');
    Route::patch('/jobs/{job}/cancel', [JobController::class, 'cancel'])->name('jobs.cancel');

    // Marsho Resources (Area & Department)
    Route::resource('areas', AreaController::class)->except(['create', 'show', 'edit']);
    Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');
    Route::resource('marsho-departments', MarshoDepartmentController::class)->except(['show', 'edit', 'create']);

    // Marsho Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/jobs/{job}/activity-logs', [ActivityLogController::class, 'showForJob'])->name('jobs.activity-logs.show');

    // Marsho Users
    Route::get('/marsho-users', [MarshoUserController::class, 'index'])->name('marsho-users.index');
    Route::post('/marsho-users', [MarshoUserController::class, 'store'])->name('marsho-users.store');

    // Marsho Reports & Export
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/marsho-jobs', [ReportController::class, 'showJobsExportPage'])->name('marsho-jobs.page');
        Route::get('/marsho-jobs/export', [ReportController::class, 'exportMarshoJobs'])->name('marsho-jobs.export');
    });

    // ==========================================
    // Kanban V2 Module Routes
    // ==========================================
    Route::prefix('kanban')->name('kanban.')->group(function () {
        // Kanban Board & Job Actions
        Route::get('/jobs', [KanbanJobController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [KanbanJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/details', [KanbanJobController::class, 'showDetails'])->name('jobs.details');
        Route::patch('/jobs/{job}/schedule', [KanbanJobController::class, 'setSchedule'])->name('jobs.schedule');
        Route::patch('/jobs/{job}/agree', [KanbanJobController::class, 'agree'])->name('jobs.agree');
        Route::patch('/jobs/{job}/return-on-hold', [KanbanJobController::class, 'returnToOnHold'])->name('jobs.return-on-hold');
        Route::patch('/jobs/{job}/re-review', [KanbanJobController::class, 'reReview'])->name('jobs.re-review');
        Route::patch('/jobs/{job}/toggle-issue', [KanbanJobController::class, 'toggleIssue'])->name('jobs.toggle-issue');
        Route::post('/jobs/{job}/split', [KanbanJobController::class, 'split'])->name('jobs.split');
        Route::match(['post', 'patch'], '/jobs/{job}/items/{item}/toggle', [KanbanJobController::class, 'toggleItem'])->name('jobs.items.toggle');
        Route::match(['post', 'patch'], '/jobs/{job}/set-preparation', [KanbanJobController::class, 'setPreparation'])->name('jobs.set-preparation');
        Route::match(['post', 'patch'], '/jobs/{job}/start', [KanbanJobController::class, 'start'])->name('jobs.start');
        Route::match(['post', 'patch'], '/jobs/{job}/change-status', [KanbanJobController::class, 'changeStatus'])->name('jobs.change-status');
        Route::post('/jobs/{job}/forward', [KanbanJobController::class, 'forward'])->name('jobs.forward');
        Route::patch('/jobs/{job}/complete', [KanbanJobController::class, 'complete'])->name('jobs.complete');
        Route::post('/jobs/{job}/close', [KanbanJobController::class, 'close'])->name('jobs.close');
        Route::post('/jobs/{job}/cancel', [KanbanJobController::class, 'cancel'])->name('jobs.cancel');

        // Kanban Areas
        Route::get('/areas', [KanbanAreaController::class, 'index'])->name('areas.index');
        Route::post('/areas', [KanbanAreaController::class, 'store'])->name('areas.store');
        Route::put('/areas/{area}', [KanbanAreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{area}', [KanbanAreaController::class, 'destroy'])->name('areas.destroy');

        // Kanban Departments
        Route::get('/departments', [KanbanDepartmentController::class, 'index'])->name('departments.index');
        Route::post('/departments', [KanbanDepartmentController::class, 'store'])->name('departments.store');
        Route::put('/departments/{department}', [KanbanDepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [KanbanDepartmentController::class, 'destroy'])->name('departments.destroy');

        // Kanban Users
        Route::get('/users', [KanbanUserController::class, 'index'])->name('users.index');
        Route::post('/users', [KanbanUserController::class, 'store'])->name('users.store');

        // Kanban Activity Logs
        Route::get('/activity-logs', [KanbanActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/jobs/{job}/activity-logs', [KanbanActivityLogController::class, 'showForJob'])->name('jobs.activity-logs.show');

        // Kanban Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/jobs', [KanbanReportController::class, 'showJobsExportPage'])->name('jobs.page');
            Route::get('/jobs/export', [KanbanReportController::class, 'exportJobs'])->name('jobs.export');
        });
    });
});

Route::get('/get-dashboard-production', [ProductionController::class, 'dashboardProduction']);
Route::get('/bar-data', [ProductionController::class, 'getBarData']);
Route::get('/data-filter', [ProductionController::class, 'filterData']);
Route::get('/year-data', [ProductionController::class, 'getBarDataByYear']);

Route::get('/area-data', [InventoryController::class, 'getAreaData']);
Route::get('/warehouse-data-filter', [InventoryController::class, 'warehouseFilterData']);
Route::get('/warehouse-dispatch-filter', [InventoryController::class, 'warehouseAreaDispatch']);
Route::get('/warehouse-data', [InventoryController::class, 'getWarehouseDataCombined'])->name('warehouse.getWarehouseData');
Route::get('/warehouse-temperature', [InventoryController::class, 'getWarehouseDataWithTemperature']);

Route::group(['middleware' => ['role:super-admin|admin']], function () {

    Route::resource('permissions', PermissionController::class);
    Route::get('permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    Route::resource('roles', RoleController::class);
    Route::get('roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);

    Route::resource('users', UserController::class);
    Route::delete('users/{userId}/delete', [UserController::class, 'destroy']);

    Route::get('departments', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('departments', [DepartmentController::class, 'store'])->name('department.store');
    Route::delete('departments/{department:department_slug}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');
    Route::put('departments/{department:department_slug}/update', [DepartmentController::class, 'update'])->name('department.update');

    Route::get('positions', [PositionController::class, 'index'])->name('position.index');
    Route::delete('positions/{position:position_slug}/delete', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::put('positions/{position:position_slug}/update', [PositionController::class, 'update'])->name('positions.update');
    Route::post('positions', [PositionController::class, 'store'])->name('position.store');

    Route::get('levels', [LevelController::class, 'index'])->name('level.index');
    Route::put('levels/{level:level_slug}/update', [LevelController::class, 'update'])->name('level.update');
    Route::post('levels', [LevelController::class, 'store'])->name('level.store');
    Route::delete('levels/{level:level_slug}/delete', [LevelController::class, 'destroy'])->name('level.destroy');
});

require __DIR__.'/auth.php';
