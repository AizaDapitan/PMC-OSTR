<?php

use App\Http\Controllers\AccessRightsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IssuedItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MCDController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestedItemController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\UserController;
use Symfony\Component\HttpKernel\DataCollector\RouterDataCollector;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name("login");
Route::post('auth/login', [LoginController::class, 'login'])->name("auth.login");
Route::get('auth/adminlogin', [LoginController::class, 'adminIndex'])->name("auth.adminlogin");
Route::post('auth/adminSubmit', [LoginController::class, 'adminSubmit'])->name("auth.adminSubmit");
Route::get('auth/forgot_password', [LoginController::class, 'forgot_password'])->name("auth.forgot_password");
Route::post('auth/sendRequest', [LoginController::class, 'sendRequest'])->name("auth.sendRequest");
Route::get('approvals/checkDetails/{id}', [ApprovalController::class, 'checkDetails']);
Route::post('approvals/getRequestedItemsSaved', [ApprovalController::class, 'getRequestedItemsSaved']);


Route::middleware(['auth'])->group(function () {

    Route::post('auth/updatePassword', [LoginController::class, 'updatePassword'])->name("auth.updatePassword");
    Route::get('auth/change_password', [LoginController::class, 'change_password'])->name("auth.change_password");
    Route::get('logout', [LoginController::class, 'logout'])->name("logout");

    // Stock Request
    Route::group(
        ['prefix' => 'stockrequests'],
        function () {
            Route::get('/main-dashboard', [StockRequestController::class, 'dashboard'])->name('stockrequests.dashboard');
            Route::get('/dashboard', [StockRequestController::class, 'index'])->name('stockrequests.index');
            Route::get('/create', [StockRequestController::class, 'create'])->name('stockrequests.create');
            Route::post('/store', [StockRequestController::class, 'store'])->name('stockrequests.store');
            Route::post('/submit', [StockRequestController::class, 'submit'])->name('stockrequests.submit');
            Route::get('/getRequests', [StockRequestController::class, 'getRequests'])->name('stockrequests.getRequests');
            Route::get('/edit/{id}', [StockRequestController::class, 'edit'])->name('stockrequests.edit');
            Route::post('/update', [StockRequestController::class, 'update'])->name('stockrequests.update');
            Route::get('/view/{id}', [StockRequestController::class, 'view'])->name('stockrequests.view');
            Route::post('/delete', [StockRequestController::class, 'delete'])->name('stockrequests.delete');
            Route::post('/autosave', [StockRequestController::class, 'autosave'])->name('stockrequests.autosave');
            Route::get('/getRequestsUnsaved', [StockRequestController::class, 'getRequestsUnsaved'])->name('stockrequests.getRequestsUnsaved');
            Route::get('/unsaved-dashboard', [StockRequestController::class, 'unsavedDashboard'])->name('stockrequests.unsavedDashboard');
            Route::get('/updateRequestApproval', [StockRequestController::class, 'updateRequestApproval'])->name('stockrequests.updateRequestApproval');
            Route::get('/insertIntoWFS', [StockRequestController::class, 'insertIntoWFS'])->name('stockrequests.insertIntoWFS');
        }
    );
    Route::group(['prefix' => 'approvals'], function () {
        Route::get('/dashboard', [ApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/getRequests', [ApprovalController::class, 'getRequests'])->name('approvals.getRequests');
        Route::get('/view/{id}', [ApprovalController::class, 'view'])->name('approvals.view');
    });
    Route::group(
        ['prefix' => 'mcds'],
        function () {
            Route::get('/dashboard', [MCDController::class, 'index'])->name('mcds.index');
            Route::get('/getRequests', [MCDController::class, 'getRequests'])->name('mcds.getRequests');
            Route::post('/receive', [MCDController::class, 'receive'])->name('mcds.receive');
            Route::get('/view/{id}', [MCDController::class, 'view'])->name('mcds.view');
            Route::get('/edit/{id}', [MCDController::class, 'edit'])->name('mcds.edit');
        }
    );

    Route::group(['prefix' => 'requested_items'], function () {
        Route::post('/store', [RequestedItemController::class, 'store'])->name('requested_items.store');
        Route::post('/getRequestedItems', [RequestedItemController::class, 'getRequestedItems'])->name('requested_items.getRequestedItems');
        Route::post('/getRequestedItemsSaved', [RequestedItemController::class, 'getRequestedItemsSaved'])->name('requested_items.getRequestedItemsSaved');
        Route::post('/update', [RequestedItemController::class, 'update'])->name('requested_items.update');
        Route::post('/delete', [RequestedItemController::class, 'delete'])->name('requested_items.delete');
    });
    Route::group(
        ['prefix' => 'issuances'],
        function () {
            Route::post('/store', [IssuedItemController::class, 'store'])->name('issuances.store');
            Route::post('/getIssuanceHistory', [IssuedItemController::class, 'getIssuanceHistory'])->name('issuances.getIssuanceHistory');
        }
    );
    Route::group(
        ['prefix' => 'products'],
        function () {
            Route::get('/getPublishedProducts', [ProductController::class, 'getPublishedProducts'])->name('products.getPublishedProducts');
        }
    );
    // User Controller
    Route::group(
        ['prefix' => 'users'],
        function () {
            Route::get('/dashboard', [UserController::class, 'index'])->name("users.index");
            Route::get('/getAllUsers', [UserController::class, 'getAllUsers'])->name("users.getAllUsers");
            Route::get('/create', [UserController::class, 'create'])->name("users.create");
            Route::get('/employee-lookup', [UserController::class, 'employee_lookup'])->name("users.employee_lookup");
            Route::post('/store', [UserController::class, 'store'])->name("users.store");
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name("users.edit");
            Route::post('/update', [UserController::class, 'update'])->name("users.update");
            Route::post('/deactivate', [UserController::class, 'deactivate'])->name("users.deactivate");
            Route::post('/activate', [UserController::class, 'activate'])->name("users.activate");
        }
    );
    // Access Rights
    Route::group(
        ['prefix' => 'accessrights'],
        function () {
            Route::get('/user', [AccessRightsController::class, 'user'])->name('accessrights.user');
            Route::get('/role', [AccessRightsController::class, 'role'])->name('accessrights.role');
            Route::get('/getUsers', [AccessRightsController::class, 'getUsers'])->name('accessrights.getUsers');
            Route::get('/getModules', [AccessRightsController::class, 'getModules'])->name('accessrights.getModules');
            Route::post('/storeUser', [AccessRightsController::class, 'storeUser'])->name('accessrights.storeUser');
            Route::post('/getUserAccessRights', [AccessRightsController::class, 'getUserAccessRights'])->name('accessrights.getUserAccessRights');
            Route::post('/storeRole', [AccessRightsController::class, 'storeRole'])->name('accessrights.storeRole');
            Route::post('/getRoleAccessRights', [AccessRightsController::class, 'getRoleAccessRights'])->name('accessrights.getRoleAccessRights');
        }
    );
    // Role Controller
    Route::group(
        ['prefix' => 'roles'],
        function () {
            Route::get('/create', [RoleController::class, 'create'])->name("roles.create");
            Route::get('/getRoles', [RoleController::class, 'getRoles'])->name("roles.getRoles");
            Route::post('/store', [RoleController::class, 'store'])->name("roles.store");
            Route::get('/dashboard', [RoleController::class, 'index'])->name("roles.index");
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name("roles.edit");
            Route::post('/update', [RoleController::class, 'update'])->name("roles.update");
            Route::get('/rolesList', [RoleController::class, 'rolesList'])->name("roles.list");
            Route::get('/rolesList_selected', [RoleController::class, 'rolesList_selected'])->name("roles.list_selected");
        }
    );
    // Permission Controller
    Route::group(
        ['prefix' => 'permissions'],
        function () {
            Route::get('/create', [PermissionController::class, 'create'])->name("permissions.create");
            Route::get('/getPermissions', [PermissionController::class, 'getPermissions'])->name("permissions.getPermissions");
            Route::post('/store', [PermissionController::class, 'store'])->name("permissions.store");
            Route::get('/dashboard', [PermissionController::class, 'index'])->name("permissions.index");
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name("permissions.edit");
            Route::post('/update', [PermissionController::class, 'update'])->name("permissions.update");
            Route::get('/modulesList', [PermissionController::class, 'modulesList'])->name("permissions.list");
            Route::get('/getPermission_selected', [PermissionController::class, 'getPermission_selected'])->name("permissions.getPermission_selected");
        }
    );
    // Report Controller
    Route::group(
        ['prefix' => 'reports'],
        function () {
            Route::get('/audit-logs', [ReportController::class, 'auditLogs'])->name("reports.auditLogs");
            Route::post('/getAuditLogs', [ReportController::class, 'getAuditLogs'])->name("reports.getAuditLogs");
            Route::get('/error-logs', [ReportController::class, 'errorLogs'])->name("reports.errorLogs");
            Route::post('/getErrorLogs', [ReportController::class, 'getErrorLogs'])->name("reports.getErrorLogs");
        }
    );
    // Application Controller
    Route::group(
        ['prefix' => 'applications'],
        function () {
            Route::get('/dashboard', [ApplicationController::class, 'index'])->name("applications.index");
            Route::get('/getScheduledShutdown', [ApplicationController::class, 'getScheduledShutdown'])->name("applications.getScheduledShutdown");
            Route::get('/create', [ApplicationController::class, 'create'])->name("applications.create");
            Route::post('/store', [ApplicationController::class, 'store'])->name("applications.store");
            Route::get('/edit/{id}', [ApplicationController::class, 'edit'])->name("applications.edit");
            Route::post('/update', [ApplicationController::class, 'update'])->name("applications.update");
            Route::post('/delete', [ApplicationController::class, 'delete'])->name("applications.delete");

            Route::post('systemDown', [ApplicationController::class, 'systemDown'])->name('applications.systemDown');
            Route::post('systemUp', [ApplicationController::class, 'systemUp'])->name('applications.systemUp');
            Route::post('reindex', [ApplicationController::class, 'reindex'])->name('applications.reindex');
            Route::get('systemDown_auto', [ApplicationController::class, 'systemDown_auto'])->name('applications.systemDown_auto');
        }
    );
});
