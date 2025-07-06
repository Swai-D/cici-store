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
            "email": "admin@example.com",
            "role": "Admin"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Register
```http
POST /api/register
Content-Type: application/json

{
    "name": "New User",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 2,
            "name": "New User",
            "email": "user@example.com",
            "role": "Cashier"
        },
        "token": "2|def456...",
        "token_type": "Bearer"
    }
}
```

### Using the Token
Include the token in the Authorization header:
```http
Authorization: Bearer 1|abc123...
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
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
    "version": "1.0.0"
}
```

## Protected Endpoints (Authentication Required)

### User Profile
```http
GET /api/user
Authorization: Bearer {token}
```

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
GET /api/products?per_page=15&page=1&category_id=1&supplier_id=1&stock_status=low
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page`: Number of items per page (default: 10)
- `page`: Page number (default: 1)
- `category_id`: Filter by category ID
- `supplier_id`: Filter by supplier ID
- `stock_status`: Filter by stock status (`low`, `out`)

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
    "sku": "PROD001",
    "barcode": "1234567890123"
}
```

#### Get Product
```http
GET /api/products/{id}
Authorization: Bearer {token}
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

**Payment Methods:** `cash`, `card`, `mobile_money`

#### Get Sale
```http
GET /api/sales/{id}
Authorization: Bearer {token}
```

#### Update Sale
```http
PUT /api/sales/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "customer_id": 1,
    "total_amount": 6000,
    "payment_method": "card"
}
```

#### Delete Sale
```http
DELETE /api/sales/{id}
Authorization: Bearer {token}
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

#### Expenses by Date
```http
GET /api/expenses/date/{date}
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

#### Get Expense
```http
GET /api/expenses/{id}
Authorization: Bearer {token}
```

#### Update Expense
```http
PUT /api/expenses/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "description": "Updated expense description",
    "amount": 60000
}
```

#### Delete Expense
```http
DELETE /api/expenses/{id}
Authorization: Bearer {token}
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

#### Get Category
```http
GET /api/categories/{id}
Authorization: Bearer {token}
```

#### Update Category
```http
PUT /api/categories/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Category Name",
    "description": "Updated description"
}
```

#### Delete Category
```http
DELETE /api/categories/{id}
Authorization: Bearer {token}
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

#### Get Supplier
```http
GET /api/suppliers/{id}
Authorization: Bearer {token}
```

#### Update Supplier
```http
PUT /api/suppliers/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Supplier Name",
    "email": "updated@example.com"
}
```

#### Delete Supplier
```http
DELETE /api/suppliers/{id}
Authorization: Bearer {token}
```

### Reports

#### Daily Report
```http
GET /api/reports/daily?date=2025-06-21
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Daily report retrieved successfully",
    "data": {
        "date": "2025-06-21",
        "sales": 50000,
        "expenses": 15000,
        "profit": 35000,
        "top_products": [...]
    }
}
```

#### Weekly Report
```http
GET /api/reports/weekly?start_date=2025-06-15&end_date=2025-06-21
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Weekly report retrieved successfully",
    "data": {
        "period": {
            "start_date": "2025-06-15",
            "end_date": "2025-06-21"
        },
        "total_sales": 350000,
        "total_expenses": 120000,
        "total_profit": 230000,
        "daily_breakdown": [...]
    }
}
```

#### Monthly Report
```http
GET /api/reports/monthly?year=2025&month=6
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Monthly report retrieved successfully",
    "data": {
        "period": {
            "year": 2025,
            "month": 6,
            "start_date": "2025-06-01",
            "end_date": "2025-06-30"
        },
        "total_sales": 1500000,
        "total_expenses": 500000,
        "total_profit": 1000000,
        "weekly_breakdown": [...]
    }
}
```

#### Profit & Loss Report
```http
GET /api/reports/profit-loss?start_date=2025-06-01&end_date=2025-06-21
Authorization: Bearer {token}
```

#### Profit & Loss Range Report
```http
GET /api/reports/profit-loss/range?start_date=2025-06-01&end_date=2025-06-21
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

## Rate Limiting

The API implements rate limiting:
- **60 requests per minute** per authenticated user
- **60 requests per minute** per IP address for unauthenticated requests

## Testing the API

### Local Testing
1. Start Laravel server: `php artisan serve`
2. Test endpoints using tools like Postman or curl
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

### 4. Product Stock Monitoring
```javascript
// Schedule trigger (every hour)
// HTTP Request node checks low stock
{
    "url": "https://your-app.railway.app/api/products?stock_status=low",
    "method": "GET",
    "headers": {
        "Authorization": "Bearer {{$node.Login.json.data.token}}",
        "Content-Type": "application/json"
    }
}
// If products found, send notification
```

## Security Considerations

1. **Token Management**: Store tokens securely in n8n credentials
2. **Rate Limiting**: API has rate limiting (60 requests per minute per user)
3. **Validation**: All inputs are validated server-side
4. **HTTPS**: Always use HTTPS in production
5. **Token Expiration**: Tokens expire after 24 hours by default
6. **Role-based Access**: Different user roles have different API access levels

## Support

For API support or questions:
- Check the health endpoint for API status
- Review error messages for troubleshooting
- Test endpoints locally before production use
- Monitor Railway logs for server issues

## Changelog

### Version 1.1.0 (2025-01-27)
- Added user registration endpoint
- Added user profile endpoint
- Added logout endpoint
- Enhanced product filtering (category, supplier, stock status)
- Added barcode field to products
- Added expense date filtering
- Added profit-loss range report
- Improved error handling and validation
- Added comprehensive rate limiting
- Enhanced webhook logging

### Version 1.0.0 (2025-06-21)
- Initial API release
- Complete CRUD operations for all entities
- Authentication with Laravel Sanctum
- Comprehensive reporting endpoints
- n8n webhook integration
- Full error handling and validation

**This API is ready for integration with n8n and other external systems!** 🚀 