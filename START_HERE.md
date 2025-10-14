# 🚀 START HERE - Eklaro Setup

## Welcome to Eklaro!

Your complete AI-powered misinformation detection platform is ready to launch.

---

## ⚡ Quick Start (2 Minutes)

### Option 1: Automated Setup (Recommended)

```bash
cd /opt/lampp/htdocs/Eklaro
./setup.sh
```

Then open: **http://localhost/Eklaro**

### Option 2: Manual Setup

```bash
# 1. Import database
mysql -u root -p < database/schema.sql

# 2. Set permissions
chmod 755 uploads

# 3. Start LAMPP
sudo /opt/lampp/lampp start
```

Then open: **http://localhost/Eklaro**

---

## 🔑 Login Credentials

**Admin Account:**
- Email: `admin@eklaro.com`
- Password: `admin123`

**Test User:**
- Email: `user@eklaro.com`
- Password: `user123`

---

## ✅ What You Get

### ✨ Complete Features:
- 🏠 **Landing Page** - Beautiful homepage with CTA
- 📄 **Article Validation** - Submit via text, URL, or file
- 📊 **Results Page** - Detailed credibility analysis
- 📈 **User Dashboard** - Track validation history
- 🔐 **Authentication** - Secure login/register
- 🛠️ **Admin Panel** - Full system management
- 🧠 **NLP Engine** - Advanced text analysis
- 🔍 **Fact-Checking** - Google API integration
- 📱 **Responsive Design** - Works on all devices

### 📁 Project Structure:
```
Eklaro/
├── admin/          → Admin panel pages
├── api/            → API endpoints
├── assets/         → CSS, JS, images
├── config/         → Configuration
├── database/       → SQL schema
├── includes/       → PHP classes
└── *.php           → Main pages
```

---

## 📚 Documentation

| File | Purpose |
|------|---------|
| **QUICK_START.md** | Fast setup guide |
| **INSTALLATION.md** | Detailed installation |
| **FEATURES.md** | Complete feature list |
| **PROJECT_SUMMARY.md** | Technical overview |
| **README.md** | Project description |

---

## 🎯 First Steps

1. ✅ **Install** - Run `./setup.sh`
2. ✅ **Login** - Use admin credentials
3. ✅ **Test** - Validate a sample article
4. ✅ **Explore** - Check dashboard and admin panel
5. ✅ **Customize** - Update config as needed

---

## 🧪 Test Article

Try validating this:

```
Scientists at MIT have published a peer-reviewed study showing 
renewable energy costs decreased by 89% over the past decade. 
The research analyzed data from over 100 countries.
```

Expected: **High credibility score** ✅

---

## 🔧 Configuration

Edit `config/config.php` to:
- Change database credentials
- Add Google Fact Check API key
- Adjust validation thresholds
- Set production mode

---

## 🌐 Key URLs

| Page | URL |
|------|-----|
| Home | http://localhost/Eklaro |
| Validate | http://localhost/Eklaro/validate |
| Dashboard | http://localhost/Eklaro/dashboard |
| Admin | http://localhost/Eklaro/admin |
| Login | http://localhost/Eklaro/login |

---

## ⚠️ Important Notes

1. **Change default passwords** immediately
2. **Add Google API key** for full fact-checking
3. **Set production mode** before deploying
4. **Backup database** regularly
5. **Review security settings** in .htaccess

---

## 🐛 Troubleshooting

### Can't access site?
```bash
sudo /opt/lampp/lampp start
```

### Database error?
```bash
mysql -u root -p < database/schema.sql
```

### Permission error?
```bash
chmod 755 uploads
```

### Still stuck?
Check `/opt/lampp/logs/php_error_log`

---

## 📊 System Requirements

- ✅ PHP 7.4+
- ✅ MySQL 5.7+
- ✅ Apache with mod_rewrite
- ✅ Modern web browser
- ✅ Internet (for fact-checking API)

---

## 🎉 You're All Set!

Everything is ready to go. Just run the setup script and start validating articles!

```bash
./setup.sh
```

**Questions?** Check the documentation files above.

**Happy Fact-Checking! 🔍**

---

*Eklaro v1.0.0 - MIT License - 2024*
