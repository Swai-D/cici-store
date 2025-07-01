# CICI Store API Integration Guide for n8n

## Overview
This guide provides comprehensive information for integrating CICI Store with n8n automation platform. The API provides full CRUD operations for all store management features including products, sales, expenses, categories, suppliers, and reports.

## Base URL
```
Production: https://your-railway-app.railway.app/api
Local: http://localhost:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Most endpoints require a Bearer token.

### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "your_password"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com"
        },
        "token": "1|abc123..."
    }
}
```

### Using the Token
Include the token in the Authorization header:
```http
Authorization: Bearer 1|abc123...
```

## Public Endpoints (No Authentication Required)

### Health Check
```http
GET /api/health
```

**Response:**
```json
{
    "status": "success",
    "message": "CICI Store API is running",
    "timestamp": "2025-06-21T17:56:53.159082Z",
    "version": "1.0.0",
    "endpoints": {
        "GET /api/health": "Health check",
        "GET /api/test": "Test endpoint",
        "POST /api/test/webhook": "Test webhook",
        "POST /api/login": "User authentication",
        "POST /api/register": "User registration"
    }
}
```

### Test Endpoint
```http
GET /api/test
```

### Test Webhook
```http
POST /api/test/webhook
Content-Type: application/json

{
    "event": "test_event",
    "data": {
        "message": "Test webhook from n8n",
        "timestamp": "2025-06-21 17:57:00"
    }
}
```

## Protected Endpoints (Authentication Required)

### Dashboard Data
```http
GET /api/dashboard
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Dashboard data retrieved successfully",
    "data": {
        "total_sales": 150000,
        "total_expenses": 45000,
        "total_profit": 105000,
        "total_products": 150,
        "low_stock_products": 5,
        "recent_sales": [...],
        "top_products": [...]
    }
}
```

### Products Management

#### List Products
```http
GET /api/products?per_page=15&page=1
Authorization: Bearer {token}
```

#### Search Products
```http
GET /api/products/search/{query}
Authorization: Bearer {token}
```

#### Low Stock Products
```http
GET /api/products/low-stock
Authorization: Bearer {token}
```

#### Create Product
```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Product Name",
    "description": "Product description",
    "price": 1000,
    "cost_price": 800,
    "quantity": 50,
    "category_id": 1,
    "supplier_id": 1,
    "sku": "PROD001"
}
```

#### Update Product
```http
PUT /api/products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Product Name",
    "price": 1200
}
```

#### Delete Product
```http
DELETE /api/products/{id}
Authorization: Bearer {token}
```

### Sales Management

#### List Sales
```http
GET /api/sales?per_page=15&page=1
Authorization: Bearer {token}
```

#### Today's Sales
```http
GET /api/sales/today
Authorization: Bearer {token}
```

#### Sales by Date
```http
GET /api/sales/date/{date}
Authorization: Bearer {token}
```

#### Create Sale
```http
POST /api/sales
Authorization: Bearer {token}
Content-Type: application/json

{
    "customer_id": 1,
    "total_amount": 5000,
    "payment_method": "cash",
    "products": [
        {
            "product_id": 1,
            "quantity": 2,
            "price": 2500
        }
    ]
}
```

### Expenses Management

#### List Expenses
```http
GET /api/expenses?per_page=15&page=1
Authorization: Bearer {token}
```

#### Today's Expenses
```http
GET /api/expenses/today
Authorization: Bearer {token}
```

#### Create Expense
```http
POST /api/expenses
Authorization: Bearer {token}
Content-Type: application/json

{
    "description": "Office supplies",
    "amount": 50000,
    "category": "Office",
    "date": "2025-06-21"
}
```

### Categories Management

#### List Categories
```http
GET /api/categories?per_page=15&page=1
Authorization: Bearer {token}
```

#### Create Category
```http
POST /api/categories
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Electronics",
    "description": "Electronic products"
}
```

### Suppliers Management

#### List Suppliers
```http
GET /api/suppliers?per_page=15&page=1
Authorization: Bearer {token}
```

#### Create Supplier
```http
POST /api/suppliers
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Supplier Name",
    "email": "supplier@example.com",
    "phone": "+255123456789",
    "address": "Dar es Salaam, Tanzania",
    "contact_person": "John Doe"
}
```

### Reports

#### Daily Report
```http
GET /api/reports/daily?date=2025-06-21
Authorization: Bearer {token}
```

#### Weekly Report
```http
GET /api/reports/weekly?start_date=2025-06-15&end_date=2025-06-21
Authorization: Bearer {token}
```

#### Monthly Report
```http
GET /api/reports/monthly?year=2025&month=6
Authorization: Bearer {token}
```

#### Profit & Loss Report
```http
GET /api/reports/profit-loss?start_date=2025-06-01&end_date=2025-06-21
Authorization: Bearer {token}
```

## n8n Webhook Integration

### Webhook Endpoint
```http
POST /api/webhook/n8n
Authorization: Bearer {token}
Content-Type: application/json

{
    "event": "sale_created",
    "data": {
        "sale_id": 123,
        "total_amount": 5000,
        "customer_name": "John Doe",
        "products": [...]
    }
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Webhook received successfully",
    "data": {
        "event": "sale_created",
        "data": {...}
    },
    "timestamp": "2025-06-21T17:57:30.123456Z"
}
```

## Error Handling

All endpoints return consistent error responses:

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common HTTP Status Codes:
- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `422` - Validation Error
- `404` - Not Found
- `500` - Server Error

## Testing the API

### Local Testing
1. Start Laravel server: `php artisan serve`
2. Run test script: `php test-api.php`
3. Verify all endpoints return proper responses

### Production Testing
Use the production URL and valid credentials to test all endpoints.

## n8n Workflow Examples

### 1. Monitor Low Stock Products
```javascript
// HTTP Request node
{
    "url": "https://your-app.railway.app/api/products/low-stock",
    "method": "GET",
    "headers": {
        "Authorization": "Bearer {{$node.Login.json.data.token}}",
        "Content-Type": "application/json"
    }
}
```

### 2. Create Sale from Webhook
```javascript
// Webhook node receives sale data
// HTTP Request node creates sale
{
    "url": "https://your-app.railway.app/api/sales",
    "method": "POST",
    "headers": {
        "Authorization": "Bearer {{$node.Login.json.data.token}}",
        "Content-Type": "application/json"
    },
    "body": {
        "customer_id": "{{$json.customer_id}}",
        "total_amount": "{{$json.total_amount}}",
        "payment_method": "{{$json.payment_method}}",
        "products": "{{$json.products}}"
    }
}
```

### 3. Daily Report Automation
```javascript
// Schedule trigger (daily at 6 PM)
// HTTP Request node gets daily report
{
    "url": "https://your-app.railway.app/api/reports/daily",
    "method": "GET",
    "headers": {
        "Authorization": "Bearer {{$node.Login.json.data.token}}",
        "Content-Type": "application/json"
    }
}
// Email node sends report
```

## Security Considerations

1. **Token Management**: Store tokens securely in n8n credentials
2. **Rate Limiting**: API has rate limiting (60 requests per minute per user)
3. **Validation**: All inputs are validated server-side
4. **HTTPS**: Always use HTTPS in production
5. **Token Expiration**: Tokens expire after 24 hours by default

## Support

For API support or questions:
- Check the health endpoint for API status
- Review error messages for troubleshooting
- Test endpoints locally before production use
- Monitor Railway logs for server issues

## Changelog

### Version 1.0.0 (2025-06-21)
- Initial API release
- Complete CRUD operations for all entities
- Authentication with Laravel Sanctum
- Comprehensive reporting endpoints
- n8n webhook integration
- Full error handling and validation 
**This API is ready for integration with n8n and other external systems!** 🚀 