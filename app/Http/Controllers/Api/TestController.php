<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test endpoint to verify API is working
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working correctly',
            'timestamp' => now(),
            'endpoints' => [
                'GET /api/health' => 'Health check',
                'POST /api/login' => 'User authentication',
                'GET /api/dashboard' => 'Dashboard data',
                'GET /api/products' => 'Products list',
                'POST /api/webhook/n8n' => 'n8n webhook'
            ]
        ]);
    }

    /**
     * Test authentication without database
     */
    public function testAuth(Request $request)
    {
        // Simulate authentication for testing
        if ($request->header('Authorization')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Authentication working',
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No authorization header'
        ], 401);
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received successfully',
            'data' => $request->all(),
            'timestamp' => now()
        ]);
    }
} 