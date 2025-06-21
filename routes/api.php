<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Test routes (no authentication required)
Route::get('/test', [TestController::class, 'test']);
Route::post('/test/webhook', [TestController::class, 'testWebhook']);

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'CICI Store API is running',
        'timestamp' => now(),
        'version' => '1.0.0',
        'endpoints' => [
            'GET /api/health' => 'Health check',
            'GET /api/test' => 'Test endpoint',
            'POST /api/test/webhook' => 'Test webhook',
            'POST /api/login' => 'User authentication',
            'POST /api/register' => 'User registration'
        ]
    ]);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Test authentication
    Route::get('/test/auth', [TestController::class, 'testAuth']);
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard data
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Products API
    Route::apiResource('products', ProductController::class);
    Route::get('/products/search/{query}', [ProductController::class, 'search']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    
    // Sales API
    Route::apiResource('sales', SaleController::class);
    Route::get('/sales/today', [SaleController::class, 'today']);
    Route::get('/sales/date/{date}', [SaleController::class, 'byDate']);
    
    // Expenses API
    Route::apiResource('expenses', ExpenseController::class);
    Route::get('/expenses/today', [ExpenseController::class, 'today']);
    Route::get('/expenses/date/{date}', [ExpenseController::class, 'byDate']);
    
    // Categories API
    Route::apiResource('categories', CategoryController::class);
    
    // Suppliers API
    Route::apiResource('suppliers', SupplierController::class);
    
    // Reports API
    Route::prefix('reports')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily']);
        Route::get('/weekly', [ReportController::class, 'weekly']);
        Route::get('/monthly', [ReportController::class, 'monthly']);
        Route::get('/profit-loss', [ReportController::class, 'profitLoss']);
        Route::get('/profit-loss/range', [ReportController::class, 'profitLossRange']);
    });
    
    // Webhook endpoint for n8n
    Route::post('/webhook/n8n', function (Request $request) {
        // Log the webhook data
        \Log::info('n8n Webhook received', $request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received successfully',
            'data' => $request->all(),
            'timestamp' => now()
        ]);
    });
});

// Fallback for undefined API routes
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found',
        'available_endpoints' => [
            'GET /api/health',
            'GET /api/test',
            'POST /api/test/webhook',
            'POST /api/login',
            'POST /api/register',
            'GET /api/dashboard',
            'GET /api/products',
            'GET /api/sales',
            'GET /api/expenses',
            'GET /api/categories',
            'GET /api/suppliers',
            'GET /api/reports/daily',
            'POST /api/webhook/n8n'
        ]
    ], 404);
}); 