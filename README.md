# Eklaro - AI-Powered Misinformation Detection Platform

## 🎯 Mission
Eklaro combats misinformation and promotes digital literacy through AI-powered article validation and fact-checking.

## 🎓 Target Audience
- Students
- Educators
- Journalists
- Researchers
- General Public

## ✨ Features

### Current Features
- **Article Validation**: Submit articles via text, URL, or file upload
- **Credibility Scoring**: AI-powered analysis with percentage scores
- **NLP Analysis**: Linguistic pattern detection for suspicious claims
- **Fact-Check Integration**: Google Fact Check Tools API integration
- **User Dashboard**: Track validation history and statistics
- **Admin Panel**: Manage users, monitor API usage, view logs
- **Responsive Design**: Mobile-first Bootstrap 5 layout

### Future Enhancements
- Sentiment analysis
- Browser extension
- Mobile app (React Native/Flutter)
- Advanced AI models (BERT, GPT)

## 🚀 Installation

### Prerequisites
- XAMPP/LAMPP with PHP 7.4+
- MySQL 5.7+
- Composer (for dependencies)

### Setup Steps

1. **Database Setup**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

2. **Configuration**
   - Copy `config/config.example.php` to `config/config.php`
   - Update database credentials
   - Add your Google Fact Check API key

3. **Install Dependencies**
   ```bash
   composer install
   ```

4. **Start Server**
   - Access via: `http://localhost/Eklaro`

## 📁 Project Structure

```
Eklaro/
├── assets/          # CSS, JS, images
├── config/          # Configuration files
├── database/        # SQL schemas and migrations
├── includes/        # PHP includes and utilities
├── modules/         # Feature modules
├── api/             # API endpoints
├── admin/           # Admin panel
└── index.php        # Landing page
```

## 🔑 Default Credentials

**Admin Account:**
- Email: admin@eklaro.com
- Password: admin123

**Test User:**
- Email: user@eklaro.com
- Password: user123

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 5
- **Icons**: Lucide Icons
- **NLP**: Custom PHP implementation + Google Fact Check API

## 📝 API Integration

### Google Fact Check Tools API
1. Get API key from [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Fact Check Tools API
3. Add key to `config/config.php`

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

MIT License - See LICENSE file for details

## 📧 Contact

For questions or support, contact: support@eklaro.com
