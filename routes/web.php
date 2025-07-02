<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Root route - redirect to appropriate page based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Health check for Railway monitoring
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'message' => 'CICI Store API is running']);
});

// Main group: all resources require auth
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view_dashboard');

    // Products
    Route::middleware('permission:view_products')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('web.products.index');
    });
    Route::middleware('permission:create_products')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('web.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('web.products.store');
    });
    Route::middleware('permission:view_products')->group(function () {
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('web.products.show');
    });
    Route::middleware('permission:edit_products')->group(function () {
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('web.products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('web.products.update');
    });
    Route::middleware('permission:delete_products')->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('web.products.destroy');
    });

    // Categories
    Route::middleware('permission:view_categories')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('web.categories.index');
    });
    Route::middleware('permission:create_categories')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('web.categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('web.categories.store');
    });
    Route::middleware('permission:view_categories')->group(function () {
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('web.categories.show');
    });
    Route::middleware('permission:edit_categories')->group(function () {
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('web.categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('web.categories.update');
    });
    Route::middleware('permission:delete_categories')->group(function () {
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('web.categories.destroy');
    });

    // Suppliers
    Route::middleware('permission:view_suppliers')->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('web.suppliers.index');
    });
    Route::middleware('permission:create_suppliers')->group(function () {
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('web.suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('web.suppliers.store');
    });
    Route::middleware('permission:view_suppliers')->group(function () {
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('web.suppliers.show');
    });
    Route::middleware('permission:edit_suppliers')->group(function () {
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('web.suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('web.suppliers.update');
    });
    Route::middleware('permission:delete_suppliers')->group(function () {
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('web.suppliers.destroy');
    });

    // Sales
    Route::middleware('permission:view_sales')->group(function () {
        Route::get('/sales', [SaleController::class, 'index'])->name('web.sales.index');
    });
    Route::middleware('permission:create_sales')->group(function () {
        Route::get('/sales/create', [SaleController::class, 'create'])->name('web.sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('web.sales.store');
    });
    Route::middleware('permission:view_sales')->group(function () {
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('web.sales.show');
    });
    Route::middleware('permission:edit_sales')->group(function () {
        Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('web.sales.edit');
        Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('web.sales.update');
    });
    Route::middleware('permission:delete_sales')->group(function () {
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('web.sales.destroy');
    });

    // Expenses
    Route::middleware('permission:view_expenses')->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('web.expenses.index');
    });
    Route::middleware('permission:create_expenses')->group(function () {
        Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('web.expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('web.expenses.store');
    });
    Route::middleware('permission:view_expenses')->group(function () {
        Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('web.expenses.show');
    });
    Route::middleware('permission:edit_expenses')->group(function () {
        Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('web.expenses.edit');
        Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('web.expenses.update');
    });
    Route::middleware('permission:delete_expenses')->group(function () {
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('web.expenses.destroy');
    });

    // Reports
    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('web.reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('web.reports.daily');
        Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('web.reports.weekly');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('web.reports.monthly');
        Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('web.reports.profit-loss');
    });

    // User Management (Admin only)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('web.users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('web.users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('web.users.store');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('web.users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('web.users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('web.users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('web.users.destroy');
        // Role Management (Admin only)
        Route::get('/roles', [RoleManagementController::class, 'index'])->name('web.roles.index');
        Route::get('/roles/create', [RoleManagementController::class, 'create'])->name('web.roles.create');
        Route::post('/roles', [RoleManagementController::class, 'store'])->name('web.roles.store');
        Route::get('/roles/{role}', [RoleManagementController::class, 'show'])->name('web.roles.show');
        Route::get('/roles/{role}/edit', [RoleManagementController::class, 'edit'])->name('web.roles.edit');
        Route::put('/roles/{role}', [RoleManagementController::class, 'update'])->name('web.roles.update');
        Route::delete('/roles/{role}', [RoleManagementController::class, 'destroy'])->name('web.roles.destroy');
    });
});

// Profile route
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';