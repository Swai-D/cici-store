<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    /**
     * Test endpoint to verify API is working
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working',
            'timestamp' => now()
        ]);
    }

    /**
     * Test authentication without database
     */
    public function testAuth(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Authentication working',
            'user' => $request->user(),
            'timestamp' => now()
        ]);
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received',
            'data' => $request->all(),
            'timestamp' => now()
        ]);
    }

    public function testDatabase()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            // Test if users table exists
            $userCount = DB::table('users')->count();
            
            // Test if personal_access_tokens table exists
            $tokenTableExists = DB::getSchemaBuilder()->hasTable('personal_access_tokens');
            
            // Check migration status
            $migrations = DB::table('migrations')->get();
            $migrationCount = $migrations->count();
            
            // Check for specific migrations
            $hasUsersMigration = $migrations->where('migration', '0001_01_01_000000_create_users_table')->count() > 0;
            $hasTokensMigration = $migrations->where('migration', '2025_06_21_204757_create_personal_access_tokens_table')->count() > 0;
            
            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'data' => [
                    'database_connected' => true,
                    'users_count' => $userCount,
                    'personal_access_tokens_table_exists' => $tokenTableExists,
                    'database_name' => DB::connection()->getDatabaseName(),
                    'migrations_count' => $migrationCount,
                    'has_users_migration' => $hasUsersMigration,
                    'has_tokens_migration' => $hasTokensMigration,
                    'recent_migrations' => $migrations->take(5)->pluck('migration')->toArray()
                ],
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Database test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ], 500);
        }
    }
} 