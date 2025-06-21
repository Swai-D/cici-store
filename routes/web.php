<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RoleManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Create routes with simple auth (no email verification required)
Route::middleware(['auth'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:create_products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:create_products');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create')->middleware('permission:create_sales');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store')->middleware('permission:create_sales');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create')->middleware('permission:create_expenses');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store')->middleware('permission:create_expenses');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view_dashboard');

    // Products - Admin and Manager can manage, Cashier can view
    Route::group([], function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:view_products');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:view_products');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:edit_products');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:edit_products');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:delete_products');
    });

    // Sales - Admin and Manager can manage, Cashier can create and view
    Route::group([], function () {
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index')->middleware('permission:view_sales');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show')->middleware('permission:view_sales');
        Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit')->middleware('permission:edit_sales');
        Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:edit_sales');
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('permission:delete_sales');
    });

    // Expenses - Admin and Manager can manage, Cashier can create and view
    Route::group([], function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index')->middleware('permission:view_expenses');
        Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show')->middleware('permission:view_expenses');
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit')->middleware('permission:edit_expenses');
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update')->middleware('permission:edit_expenses');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('permission:delete_expenses');
    });

    // Reports - Admin and Manager can access all reports, Cashier can view basic reports
    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    });

    // User Management (Admin only)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        
        // Role Management (Admin only)
        Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleManagementController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [RoleManagementController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleManagementController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleManagementController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
