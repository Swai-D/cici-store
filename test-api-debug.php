<?php

// Test script to debug API issues
$baseUrl = 'https://cici-store-production.up.railway.app/api';

echo "=== CICI Store API Debug Test ===\n\n";

// Test 1: Health check
echo "1. Testing health endpoint...\n";
$response = file_get_contents($baseUrl . '/health');
if ($response !== false) {
    $data = json_decode($response, true);
    echo "✅ Health check: " . $data['message'] . "\n";
} else {
    echo "❌ Health check failed\n";
}
echo "\n";

// Test 2: Basic test endpoint
echo "2. Testing basic test endpoint...\n";
$response = file_get_contents($baseUrl . '/test');
if ($response !== false) {
    $data = json_decode($response, true);
    echo "✅ Basic test: " . $data['message'] . "\n";
} else {
    echo "❌ Basic test failed\n";
}
echo "\n";

// Test 3: Database connection test
echo "3. Testing database connection...\n";
$response = file_get_contents($baseUrl . '/test/database');
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data['status'] === 'success') {
        echo "✅ Database connection: " . $data['message'] . "\n";
        echo "   - Users count: " . $data['data']['users_count'] . "\n";
        echo "   - Personal access tokens table exists: " . ($data['data']['personal_access_tokens_table_exists'] ? 'Yes' : 'No') . "\n";
        echo "   - Database name: " . $data['data']['database_name'] . "\n";
    } else {
        echo "❌ Database connection failed: " . $data['message'] . "\n";
        if (isset($data['error'])) {
            echo "   Error: " . $data['error'] . "\n";
        }
    }
} else {
    echo "❌ Database test failed\n";
}
echo "\n";

// Test 4: Login endpoint test
echo "4. Testing login endpoint...\n";
$loginData = [
    'email' => 'cici-store-chatbot@gmail.com',
    'password' => 'davyswai1995'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        'content' => json_encode($loginData)
    ]
]);

$response = file_get_contents($baseUrl . '/login', false, $context);
if ($response !== false) {
    $data = json_decode($response, true);
    if ($data['status'] === 'success') {
        echo "✅ Login successful!\n";
        echo "   - User: " . $data['data']['user']['name'] . " (" . $data['data']['user']['email'] . ")\n";
        echo "   - Role: " . $data['data']['user']['role'] . "\n";
        echo "   - Token type: " . $data['data']['token_type'] . "\n";
        echo "   - Token: " . substr($data['data']['token'], 0, 20) . "...\n";
    } else {
        echo "❌ Login failed: " . $data['message'] . "\n";
        if (isset($data['errors'])) {
            echo "   Validation errors: " . json_encode($data['errors']) . "\n";
        }
        if (isset($data['debug_info'])) {
            echo "   Debug info: " . $data['debug_info'] . "\n";
        }
    }
} else {
    echo "❌ Login request failed\n";
}
echo "\n";

echo "=== Debug Test Complete ===\n"; 