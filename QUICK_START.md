# Eklaro Quick Start Guide

## 🚀 Fast Installation (3 Steps)

### Step 1: Run Setup Script
```bash
cd /opt/lampp/htdocs/Eklaro
./setup.sh
```

### Step 2: Access Application
Open your browser and go to:
```
http://localhost/Eklaro
```

### Step 3: Login
Use these default credentials:
- **Admin:** admin@eklaro.com / admin123
- **User:** user@eklaro.com / user123

## ✅ That's it! You're ready to go!

---

## 📖 Common Tasks

### Validate Your First Article

1. Click **"Start Validating"** on homepage
2. Choose input method (Text, URL, or File)
3. Paste or upload your article
4. Click **"Analyze Article"**
5. View detailed results with credibility score

### Access Dashboard

1. Login to your account
2. Click **"Dashboard"** in navigation
3. View your validation history and statistics

### Admin Panel (Admin Only)

1. Login as admin
2. Click **"Admin"** in navigation
3. Manage users, view articles, check logs

---

## 🔧 Manual Installation

If the setup script doesn't work:

### 1. Create Database
```bash
mysql -u root -p
```
```sql
SOURCE /opt/lampp/htdocs/Eklaro/database/schema.sql;
exit;
```

### 2. Set Permissions
```bash
chmod 755 /opt/lampp/htdocs/Eklaro/uploads
```

### 3. Start Services
```bash
sudo /opt/lampp/lampp start
```

---

## 🎯 Key Features to Try

### 1. Article Validation
- **Text Input:** Paste any article text
- **URL Input:** Enter article URL
- **File Upload:** Drag & drop .txt files

### 2. Credibility Analysis
- View credibility score (0-100%)
- See suspicious claims highlighted
- Check fact-check matches
- Read detailed explanations

### 3. Dashboard
- Track validation history
- View statistics
- Monitor average scores

### 4. Admin Features
- Manage all users
- View all articles
- Monitor system activity
- Check API usage

---

## 🔑 Important URLs

| Page | URL |
|------|-----|
| Homepage | http://localhost/Eklaro |
| Validate | http://localhost/Eklaro/validate |
| Login | http://localhost/Eklaro/login |
| Register | http://localhost/Eklaro/register |
| Dashboard | http://localhost/Eklaro/dashboard |
| Admin Panel | http://localhost/Eklaro/admin |
| About | http://localhost/Eklaro/about |
| Contact | http://localhost/Eklaro/contact |

---

## 🐛 Troubleshooting

### Can't access the site?
```bash
# Check if Apache is running
sudo /opt/lampp/lampp status

# Start Apache if needed
sudo /opt/lampp/lampp startapache
```

### Database connection error?
```bash
# Check if MySQL is running
sudo /opt/lampp/lampp status

# Start MySQL if needed
sudo /opt/lampp/lampp startmysql
```

### Permission denied?
```bash
# Fix permissions
chmod 755 /opt/lampp/htdocs/Eklaro/uploads
```

### Blank page?
- Check PHP error logs: `/opt/lampp/logs/php_error_log`
- Verify PHP version: `php -v` (need 7.4+)

---

## 📝 Test Article Examples

### Example 1: High Credibility
```
Scientists at MIT have published a peer-reviewed study in Nature journal 
showing that renewable energy costs have decreased by 89% over the past decade. 
The research, conducted by Dr. Jane Smith and her team, analyzed data from 
over 100 countries between 2010 and 2020.
```

### Example 2: Low Credibility
```
SHOCKING!!! Scientists don't want you to know this AMAZING secret!!! 
Everyone is talking about this miracle cure that will COMPLETELY change 
your life FOREVER!!! Click here NOW!!!
```

### Example 3: Mixed Credibility
```
Some experts claim that the new technology could revolutionize the industry. 
While the results are promising, more research is needed to verify these claims. 
The company allegedly plans to launch next year, though no official 
announcement has been made.
```

---

## 🎓 Learning Path

1. **Beginner:** Validate a few test articles
2. **Intermediate:** Create an account and track history
3. **Advanced:** Explore admin panel (if admin)
4. **Expert:** Integrate with Google Fact Check API

---

## 📞 Need Help?

- **Documentation:** See README.md and INSTALLATION.md
- **Features:** See FEATURES.md for complete list
- **Email:** support@eklaro.com
- **Issues:** Check error logs first

---

## 🎉 Next Steps

1. ✅ Complete installation
2. ✅ Test article validation
3. ✅ Create user account
4. ✅ Explore dashboard
5. ✅ Try admin panel (if admin)
6. 🔜 Add Google Fact Check API key (optional)
7. 🔜 Customize for your needs
8. 🔜 Deploy to production (optional)

---

**Happy Fact-Checking! 🔍**
