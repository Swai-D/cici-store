# CICI Store - Business Management System

A comprehensive Laravel-based business management system designed for small businesses in Tanzania. This application helps manage products, track sales, monitor expenses, and generate detailed reports.

## 🏪 Features

### Core Functionality
- **Product Management**: Add, edit, and track inventory
- **Sales Tracking**: Record sales with automatic stock updates
- **Expense Management**: Track business expenses by category
- **Customer Management**: Store customer information
- **Supplier Management**: Manage supplier relationships
- **Reporting**: Generate daily, weekly, monthly, and profit-loss reports

### Business Intelligence
- **Dashboard**: Real-time overview of business performance
- **Charts & Analytics**: Visual representation of sales and expenses
- **Stock Alerts**: Low stock notifications
- **Profit Calculations**: Automatic profit margin calculations
- **Payment Tracking**: Multiple payment method support (Cash, M-Pesa, TigoPesa, Bank)

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.1+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze
- **Charts**: Chart.js
- **Interactive Components**: Livewire

## 📋 Requirements

- PHP >= 8.1
- MySQL >= 5.7 or MariaDB >= 10.2
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd cici-store
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cici_store
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Storage Setup
```bash
php artisan storage:link
```

### 8. Start Development Server
```bash
php artisan serve
```

## 📊 Database Structure

### Core Tables
- **users**: User authentication and profiles
- **categories**: Product categories
- **suppliers**: Supplier information
- **products**: Product inventory with pricing
- **sales**: Sales transactions
- **expenses**: Business expenses
- **customers**: Customer information

### Key Relationships
- Products belong to categories and suppliers
- Sales are linked to products and customers
- Expenses are categorized for reporting

## 🔐 Security Features

- **Authentication**: Secure user login/registration
- **Authorization**: Role-based access control
- **CSRF Protection**: Built-in CSRF token validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping

## 📈 Reporting System

### Available Reports
1. **Daily Reports**: Sales by hour, daily totals
2. **Weekly Reports**: Sales by day, top products
3. **Monthly Reports**: Comprehensive monthly analysis
4. **Profit & Loss**: Detailed financial analysis

### Chart Types
- Line charts for time-series data
- Bar charts for categorical comparisons
- Pie charts for expense breakdowns

## 🎨 User Interface

- **Responsive Design**: Works on desktop, tablet, and mobile
- **Modern UI**: Clean, professional interface using Tailwind CSS
- **Intuitive Navigation**: Easy-to-use menu system
- **Data Tables**: Sortable and searchable data tables
- **Form Validation**: Real-time form validation with error messages

## 🔧 Configuration

### Timezone
The application is configured for Tanzania timezone (`Africa/Dar_es_Salaam`)

### Currency
All monetary values are displayed in Tanzanian Shillings (Tsh)

### Payment Methods
- Cash
- M-Pesa
- TigoPesa
- Bank Transfer

## 📱 Mobile Responsiveness

The application is fully responsive and optimized for:
- Desktop computers
- Tablets
- Mobile phones

## 🚀 Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed deployment instructions.

## 🐛 Troubleshooting

### Common Issues
1. **500 Error**: Check storage permissions and APP_KEY
2. **Database Connection**: Verify DB credentials in `.env`
3. **Assets not loading**: Run `npm run build` and check `storage:link`

### Useful Commands
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📞 Support

For technical support or questions:
- Check the Laravel documentation
- Review the deployment guide
- Contact your system administrator

## 📄 License

This project is licensed under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

**CICI Store** - Empowering small businesses in Tanzania with modern business management tools.
