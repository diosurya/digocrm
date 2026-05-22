<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Activities
    Route::resource('activities', ActivityController::class);

    Route::resource('products', ProductController::class);

    // Sales Orders
    Route::resource('orders', SalesOrderController::class);

    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::get('customers/template', [CustomerController::class, 'downloadTemplate'])->name('customers.template');
    Route::resource('customers', CustomerController::class);

    Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
    Route::post('leads/import', [LeadController::class, 'import'])->name('leads.import');
    Route::get('leads/template', [LeadController::class, 'downloadTemplate'])->name('leads.template');
    Route::resource('leads', LeadController::class);
    Route::post('leads/{lead}/log-activity', [LeadController::class, 'logActivity'])->name('leads.log-activity');
    Route::post('leads/{lead}/convert', [LeadController::class, 'convertToCustomer'])->name('leads.convert');

    Route::get('tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::post('tasks/import', [TaskController::class, 'import'])->name('tasks.import');
    Route::get('tasks/template', [TaskController::class, 'downloadTemplate'])->name('tasks.template');
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

    // User Management: Superadmin manages all, Manager manages team
    Route::middleware(['role:superadmin,manager_marketing'])->group(function () {
        Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
        Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
        Route::resource('users', UserController::class);
    });

    // Superadmin Exclusive: Core Settings & Roles
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/logs', [SettingController::class, 'logs'])->name('settings.logs');
        Route::get('/settings/audit-logs', function() {
            return view('settings.audit_logs');
        })->name('settings.audit-logs');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-email', [SettingController::class, 'testEmail'])->name('settings.test-email');
        
        Route::resource('accounts', \App\Http\Controllers\AccountController::class);
        Route::resource('roles', RoleController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
