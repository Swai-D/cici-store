# CICI Store - Business Management System

A comprehensive Laravel 11 web application for managing a small business store in Tanzania. This application provides complete business management capabilities including inventory management, sales tracking, expense management, role-based access control, and detailed reporting with interactive charts.

## 🚀 Features

### 🔐 Authentication & User Management
- **Laravel Breeze Authentication**: Secure login/logout system with email verification
- **Advanced Role-Based Access Control**: Admin, Manager, and Cashier roles with granular permissions
- **Permission Management**: Fine-grained permissions for all system features
- **User Management**: Create, edit, and manage user accounts with role assignments
- **Email Verification**: Secure email verification for new accounts
- **Password Reset**: Secure password reset functionality

### 📦 Product Management
- **Complete Inventory Tracking**: Full product catalog with stock management
- **Category Management**: Organize products by categories with CRUD operations
- **Supplier Management**: Track product suppliers and contact information
- **Stock Alerts**: Automatic low stock notifications
- **Product Search & Filtering**: Advanced search capabilities
- **Product CRUD**: Create, Read, Update, Delete operations for all products

### 💰 Sales Management
- **Complete Sales Recording**: Track all sales transactions with customer details
- **Customer Management**: Maintain comprehensive customer database
- **Stock Updates**: Automatic inventory updates on sales
- **Sales History**: Complete transaction history with detailed reports
- **Sales Analytics**: Daily, weekly, and monthly sales analytics
- **Sales CRUD**: Full Create, Read, Update, Delete operations for sales

### 💸 Expense Tracking
- **Expense Categories**: Categorize business expenses
- **Expense Recording**: Track all business expenditures
- **Expense Reports**: Detailed expense analysis and reporting
- **Expense CRUD**: Complete expense management operations

### 📊 Advanced Reporting & Analytics
- **Interactive Dashboard**: Real-time business metrics with Chart.js
- **Sales Charts**: Visual representation of sales data with multiple chart types
- **Profit & Loss Reports**: Comprehensive financial reporting
- **Inventory Reports**: Stock level analysis and trends
- **Custom Date Range Reports**: Flexible reporting periods (Daily, Weekly, Monthly)
- **Revenue Analytics**: Detailed revenue tracking and analysis
- **Expense Analytics**: Comprehensive expense breakdown and trends

### 🎨 Modern User Interface
- **Responsive Design**: Clean, modern UI with Tailwind CSS
- **Livewire Components**: Dynamic, interactive user experience
- **Mobile Responsive**: Optimized for all device sizes
- **Swahili Language Support**: Localized for Tanzanian market
- **Interactive Charts**: Beautiful data visualization with Chart.js
- **Modal Dialogs**: Smooth user interactions for forms and confirmations

### 🔒 Security & Access Control
- **Role-Based Permissions**: Granular access control for all features
- **Middleware Protection**: Route-level security with permission checks
- **CSRF Protection**: Built-in CSRF protection on all forms
- **Input Validation**: Comprehensive form validation and sanitization
- **Session Management**: Secure session handling

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Interactive Components**: Livewire 3
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js with custom integration
- **Role Management**: Spatie Laravel Permission (Latest Version)
- **UI Framework**: Tailwind CSS with custom components

## 📋 Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM
- Git

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd cici-store
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cici_store
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database with sample data**
   ```bash
   php artisan db:seed
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## 👥 Default Users & Roles

After seeding the database, you can log in with these default accounts:

### Admin User (Super Admin)
- **Email**: admin@cici.com
- **Password**: password
- **Role**: Admin (Full access to all features)
- **Permissions**: All system permissions including user and role management

### Manager User
- **Email**: manager@cici.com
- **Password**: password
- **Role**: Manager (Sales, Products, Reports access)
- **Permissions**: View and manage products, sales, expenses, reports, categories, suppliers, customers

### Cashier User
- **Email**: cashier@cici.com
- **Password**: password
- **Role**: Cashier (Limited access)
- **Permissions**: View products, create sales, view basic reports

## 🔐 Role-Based Permissions

The system includes comprehensive role-based access control with the following permissions:

### Admin Permissions
- **Full System Access**: All features and functions
- **User Management**: Create, edit, delete users
- **Role Management**: Create, edit, delete roles and permissions
- **System Configuration**: Access to all system settings

### Manager Permissions
- **Product Management**: Full CRUD operations on products
- **Sales Management**: Full CRUD operations on sales
- **Expense Management**: Full CRUD operations on expenses
- **Report Access**: All reporting features
- **Category Management**: Manage product categories
- **Supplier Management**: Manage suppliers
- **Customer Management**: Manage customers

### Cashier Permissions
- **Product Viewing**: View product catalog and stock levels
- **Sales Creation**: Create new sales transactions
- **Basic Reports**: Access to basic sales reports
- **Customer Viewing**: View customer information

## 📁 Project Structure

```
cici-store/
├── app/
│   ├── Console/Commands/          # Custom Artisan commands
│   │   ├── CreateMissingRoles.php # Role creation command
│   │   ├── CreateUser.php         # User creation command
│   │   ├── ListRoles.php          # Role listing command
│   │   └── ListUsers.php          # User listing command
│   ├── Http/Controllers/          # Application controllers
│   │   ├── Auth/                  # Authentication controllers
│   │   │   └── VerifyEmailController.php
│   │   ├── DashboardController    # Dashboard and analytics
│   │   ├── ProductController      # Product management
│   │   ├── SaleController         # Sales management
│   │   ├── ExpenseController      # Expense tracking
│   │   ├── ReportController       # Reporting system with Chart.js
│   │   ├── UserManagementController # User management
│   │   └── RoleManagementController # Role and permission management
│   ├── Http/Middleware/           # Custom middleware
│   │   └── CheckRole.php          # Role checking middleware
│   ├── Livewire/                  # Livewire components
│   │   ├── Actions/               # Livewire actions
│   │   └── Forms/                 # Livewire forms
│   ├── Models/                    # Eloquent models
│   │   ├── Category.php           # Product categories
│   │   ├── Customer.php           # Customer management
│   │   ├── Expense.php            # Expense tracking
│   │   ├── Product.php            # Product management
│   │   ├── Sale.php               # Sales management
│   │   ├── Supplier.php           # Supplier management
│   │   └── User.php               # User management with roles
│   └── Providers/                 # Service providers
├── database/
│   ├── migrations/                # Database migrations
│   │   ├── Categories table       # Product categories
│   │   ├── Suppliers table        # Supplier information
│   │   ├── Products table         # Product catalog
│   │   ├── Sales table            # Sales transactions
│   │   ├── Expenses table         # Business expenses
│   │   ├── Customers table        # Customer database
│   │   └── Permission tables      # Role and permission system
│   ├── seeders/                   # Database seeders
│   │   ├── AdminUserSeeder.php    # Admin user creation
│   │   ├── RolePermissionSeeder.php # Roles and permissions
│   │   ├── CategorySeeder.php     # Sample categories
│   │   ├── SupplierSeeder.php     # Sample suppliers
│   │   ├── ProductSeeder.php      # Sample products
│   │   └── SampleDataSeeder.php   # Complete sample data
│   └── factories/                 # Model factories
├── resources/
│   └── views/                     # Blade templates
│       ├── components/            # Reusable UI components
│       ├── dashboard.blade.php    # Main dashboard with charts
│       ├── products/              # Product management views
│       │   ├── index.blade.php    # Product listing
│       │   ├── create.blade.php   # Create product form
│       │   ├── edit.blade.php     # Edit product form
│       │   └── show.blade.php     # Product details
│       ├── sales/                 # Sales management views
│       │   ├── index.blade.php    # Sales listing
│       │   ├── create.blade.php   # Create sale form
│       │   ├── edit.blade.php     # Edit sale form
│       │   └── show.blade.php     # Sale details
│       ├── expenses/              # Expense management views
│       │   ├── index.blade.php    # Expense listing
│       │   ├── create.blade.php   # Create expense form
│       │   ├── edit.blade.php     # Edit expense form
│       │   └── show.blade.php     # Expense details
│       ├── reports/               # Reporting views
│       │   ├── index.blade.php    # Reports dashboard
│       │   ├── daily.blade.php    # Daily reports
│       │   ├── weekly.blade.php   # Weekly reports
│       │   ├── monthly.blade.php  # Monthly reports
│       │   └── profit-loss.blade.php # Profit & loss reports
│       ├── users/                 # User management views
│       │   ├── index.blade.php    # User listing
│       │   ├── create.blade.php   # Create user form
│       │   ├── edit.blade.php     # Edit user form
│       │   └── show.blade.php     # User details
│       └── roles/                 # Role management views
│           ├── index.blade.php    # Role listing
│           ├── create.blade.php   # Create role form
│           ├── edit.blade.php     # Edit role form
│           └── show.blade.php     # Role details
└── routes/
    ├── web.php                    # Web routes with permission middleware
    └── auth.php                   # Authentication routes
```

## 🔧 Key Features Implementation

### Advanced Dashboard Analytics
- **Real-time Metrics**: Live sales, revenue, and expense tracking
- **Chart.js Integration**: Beautiful interactive charts for data visualization
- **Multiple Chart Types**: Line charts, bar charts, and pie charts
- **Daily, Weekly, Monthly Trends**: Comprehensive time-based analytics
- **Stock Level Indicators**: Visual stock status and alerts

### Comprehensive Stock Management
- **Automatic Stock Updates**: Real-time inventory updates on sales
- **Low Stock Alerts**: Proactive stock level notifications
- **Advanced Product Search**: Filter by name, category, supplier
- **Category-based Organization**: Hierarchical product structure
- **Supplier Tracking**: Complete supplier relationship management

### Complete Sales Tracking System
- **Transaction Recording**: Detailed sales with customer information
- **Customer Database**: Comprehensive customer management
- **Sales History**: Complete transaction history with search
- **Automatic Inventory Updates**: Real-time stock adjustments
- **Sales Analytics**: Revenue tracking and trend analysis

### Advanced User & Role Management
- **Role-Based Access Control**: Granular permission system
- **Permission Management**: Fine-grained feature access control
- **User Creation & Editing**: Complete user lifecycle management
- **Secure Authentication**: Laravel Breeze with email verification
- **Middleware Protection**: Route-level security enforcement

### Interactive Reporting System
- **Chart.js Integration**: Beautiful data visualization
- **Multiple Report Types**: Daily, weekly, monthly, profit-loss
- **Custom Date Ranges**: Flexible reporting periods
- **Export Capabilities**: Data export functionality
- **Real-time Data**: Live report updates

## 🚀 Deployment

### Production Deployment
1. Set up your production server with PHP 8.2+, MySQL, and Nginx/Apache
2. Clone the repository to your server
3. Install dependencies: `composer install --optimize-autoloader --no-dev`
4. Build assets: `npm run build`
5. Configure environment variables for production
6. Run migrations: `php artisan migrate --force`
7. Seed the database: `php artisan db:seed`
8. Set up proper file permissions
9. Configure web server to point to the `public` directory

### Environment Variables
Make sure to configure these environment variables for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔒 Security Features

- **CSRF Protection**: Built-in CSRF protection on all forms
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Protection**: Blade templating with automatic escaping
- **Role-Based Access Control**: Granular permission system
- **Secure Password Hashing**: Laravel's bcrypt hashing
- **Session Management**: Secure session handling
- **Input Validation**: Comprehensive form validation and sanitization
- **Middleware Protection**: Route-level security enforcement

## 📊 Database Schema

The application includes the following main tables:
- `users` - User accounts with role assignments
- `products` - Product catalog and inventory
- `categories` - Product categorization
- `suppliers` - Supplier information
- `sales` - Sales transactions with customer details
- `customers` - Customer database
- `expenses` - Business expenses with categories
- `roles` - User roles for access control
- `permissions` - System permissions
- `model_has_roles` - Role assignments
- `model_has_permissions` - Permission assignments
- `role_has_permissions` - Role-permission relationships

## 🎯 Business Features

### Inventory Management
- **Product Catalog**: Complete product database with images and descriptions
- **Stock Tracking**: Real-time inventory levels with automatic updates
- **Category Management**: Organized product categorization
- **Supplier Management**: Complete supplier relationship tracking
- **Low Stock Alerts**: Proactive inventory management

### Sales Management
- **Transaction Recording**: Complete sales with customer details
- **Customer Database**: Comprehensive customer information
- **Sales Analytics**: Revenue tracking and trend analysis
- **Receipt Generation**: Professional sales receipts
- **Sales History**: Complete transaction history

### Financial Management
- **Expense Tracking**: Categorized business expenses
- **Profit & Loss Analysis**: Comprehensive financial reporting
- **Revenue Analytics**: Detailed revenue tracking
- **Cost Analysis**: Expense breakdown and trends
- **Financial Reports**: Daily, weekly, monthly financial summaries

### Reporting & Analytics
- **Interactive Dashboards**: Real-time business metrics
- **Chart Visualizations**: Beautiful data representation
- **Custom Reports**: Flexible reporting periods
- **Export Capabilities**: Data export functionality
- **Trend Analysis**: Historical data analysis

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the Laravel documentation for framework-specific questions

## 🔄 Updates and Maintenance

### Regular Maintenance Tasks
- Update dependencies regularly (`composer update`, `npm update`)
- Monitor database performance and optimize queries
- Backup database regularly
- Check for security updates
- Monitor application logs for errors

### Future Enhancements
- **Mobile App Development**: Flutter-based mobile application
- **Advanced Analytics**: Machine learning-powered insights
- **Payment Gateway Integration**: M-Pesa, Airtel Money integration
- **Multi-store Support**: Franchise and multi-location management
- **Advanced Reporting**: Custom report builder
- **Inventory Forecasting**: AI-powered stock predictions
- **Customer Relationship Management**: Advanced CRM features
- **API Development**: RESTful API for third-party integrations

## 🏆 Key Achievements

- ✅ Complete business management system
- ✅ Advanced role-based access control
- ✅ Interactive reporting with Chart.js
- ✅ Responsive design for all devices
- ✅ Comprehensive CRUD operations
- ✅ Secure authentication system
- ✅ Production-ready deployment
- ✅ Swahili language support
- ✅ Modern UI/UX design

---

**CICI Store** - Empowering small businesses in Tanzania with modern, comprehensive business management tools. Built with Laravel 11, featuring advanced role-based access control, interactive analytics, and a beautiful user interface designed for the Tanzanian market.
