# AI Business Consultant - Implementation Guide

## 🚀 Overview

The AI Business Consultant is a powerful feature that provides intelligent business insights and recommendations based on your store's data. It analyzes products, sales, expenses, and other business metrics to provide actionable advice in Kiswahili.

## 📋 Features

- **Intelligent Analysis**: Analyzes your business data to identify trends and opportunities
- **Actionable Recommendations**: Provides specific, actionable advice for business improvement
- **Kiswahili Support**: Responds in clear, professional Kiswahili
- **Time Range Selection**: Analyze data for different time periods (week, month, 30/90 days)
- **Secure API Integration**: Uses encrypted storage for OpenAI API keys
- **Role-Based Access**: Different permissions for admins and users

## 🛠️ Installation Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Run Seeders
```bash
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=AiPermissionSeeder
```

### 3. Install Dependencies (if not already installed)
```bash
composer require guzzlehttp/guzzle
```

### 4. Configure OpenAI API Key
1. Go to `/admin/ai` in your application
2. Enable the AI feature
3. Enter your OpenAI API key (get one from https://platform.openai.com/)
4. Save settings

## 🔧 Configuration

### Environment Variables
Add these to your `.env` file (optional, can be set via admin panel):
```env
OPENAI_API_KEY=your_openai_api_key_here
AI_ENABLED=true
```

### Permissions
The system creates two AI-related permissions:
- `manage_ai`: Can configure AI settings (Admin only)
- `use_ai`: Can use the AI Business Consultant (Admin, Manager)

## 📱 Usage

### Admin Settings Page
- **URL**: `/admin/ai`
- **Purpose**: Configure OpenAI API key and enable/disable AI
- **Access**: Users with `manage_ai` permission

### AI Chat Page
- **URL**: `/ai-chat`
- **Purpose**: Interactive chat with AI Business Consultant
- **Access**: Users with `use_ai` permission

### API Endpoint
- **URL**: `POST /api/ai/chat`
- **Purpose**: Programmatic access to AI functionality
- **Authentication**: Required (Sanctum)

## 💬 Example Questions

Here are some example questions you can ask the AI:

### Product Analysis
- "Bidhaa gani zina faida kubwa?"
- "Ni bidhaa zipi zinazosimama?"
- "Tunapaswa kuongeza stock ya bidhaa gani?"

### Sales Analysis
- "Mauzo ya wiki hii yalikuwa vipi?"
- "Ni siku zipi zinazofanya mauzo mengi?"
- "Kuna mwelekeo gani kwenye mauzo?"

### Expense Management
- "Gharama zipi zinaweza kupunguzwa?"
- "Tunatumia pesa nyingi kwenye nini?"
- "Kuna matumizi yasiyo na tija?"

### Business Strategy
- "Nifanye nini kuongeza faida?"
- "Mpango wa wiki ijayo uwe vipi?"
- "Ninawezaje kuboresha biashara?"

## 🔒 Security Features

### API Key Security
- API keys are encrypted using Laravel's built-in encryption
- Keys are masked in the UI (shows only first 4 and last 4 characters)
- Keys can be updated without exposing the current value

### Access Control
- Role-based permissions control who can use AI features
- API endpoints require authentication
- All requests are logged for audit purposes

### Rate Limiting
- Built-in Laravel rate limiting can be applied
- Consider adding Redis-based rate limiting for production

## 🎨 UI Components

### React Component
- **File**: `resources/js/components/AdminAiChat.jsx`
- **Features**: Full-featured chat interface with real-time updates
- **Usage**: Import and use in React applications

### Blade Component
- **File**: `resources/views/components/ai-chat-widget.blade.php`
- **Features**: Alpine.js powered chat widget
- **Usage**: Include with `<x-ai-chat-widget />`

## 🔧 Customization

### System Prompt
Edit the `systemPrompt()` method in `AiChatController` to customize the AI's personality and expertise.

### Data Sources
Modify the `loadBusinessData()` method to include additional data sources or change the analysis period.

### UI Styling
Both components use Tailwind CSS and can be customized by modifying the CSS classes.

## 📊 Data Analysis

The AI analyzes the following data:

### Products
- Product names, SKUs, costs, prices, stock levels
- Category and supplier information
- Profit margins and performance metrics

### Sales
- Sales transactions with quantities and profits
- Customer information
- Time-based sales patterns

### Expenses
- Expense categories and amounts
- Spending patterns and trends
- Cost optimization opportunities

### Categories & Suppliers
- Performance by category
- Supplier relationships and costs

## 🚨 Troubleshooting

### Common Issues

1. **"AI is disabled or API key missing"**
   - Check if AI is enabled in admin settings
   - Verify OpenAI API key is set correctly
   - Ensure API key has sufficient credits

2. **"Hitilafu ya mtandao/AI"**
   - Check internet connectivity
   - Verify OpenAI API is accessible
   - Check Laravel logs for detailed errors

3. **Permission Denied**
   - Ensure user has appropriate permissions
   - Check role assignments
   - Verify middleware configuration

### Debug Mode
Enable debug mode in `.env` to see detailed error messages:
```env
APP_DEBUG=true
```

## 📈 Performance Optimization

### Caching
Consider implementing caching for:
- Business data queries
- AI responses for common questions
- User permissions

### Database Optimization
- Add indexes on frequently queried columns
- Optimize date range queries
- Consider data archiving for old records

### API Optimization
- Implement response caching
- Use connection pooling for database queries
- Consider async processing for long-running analyses

## 🔮 Future Enhancements

### Potential Features
- **Conversation History**: Save and retrieve past conversations
- **Custom Prompts**: Allow users to create custom analysis prompts
- **Scheduled Reports**: Automatic AI-generated reports
- **Integration**: Connect with external data sources
- **Multi-language**: Support for additional languages
- **Voice Interface**: Voice-to-text and text-to-speech

### Advanced Analytics
- **Predictive Analytics**: Forecast sales and trends
- **Anomaly Detection**: Identify unusual patterns
- **Competitive Analysis**: Compare with industry benchmarks
- **ROI Calculations**: Detailed return on investment analysis

## 📞 Support

For technical support or questions about the AI Business Consultant:

1. Check the Laravel logs for error details
2. Verify OpenAI API key and credits
3. Test with simple questions first
4. Ensure all permissions are properly set

## 📝 Changelog

### Version 1.0.0
- Initial implementation
- Basic chat functionality
- Admin settings interface
- Role-based permissions
- Kiswahili language support
- Business data analysis
- OpenAI GPT-4o-mini integration
