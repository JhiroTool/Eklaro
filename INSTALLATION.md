# Eklaro Installation Guide

## Prerequisites

Before installing Eklaro, ensure you have:

- **XAMPP/LAMPP** with PHP 7.4 or higher
- **MySQL** 5.7 or higher
- **Web Browser** (Chrome, Firefox, Safari, or Edge)

## Step-by-Step Installation

### 1. Database Setup

1. Start your MySQL server:
   ```bash
   sudo /opt/lampp/lampp startmysql
   ```

2. Import the database schema:
   ```bash
   mysql -u root -p < /opt/lampp/htdocs/Eklaro/database/schema.sql
   ```
   
   Or use phpMyAdmin:
   - Navigate to `http://localhost/phpmyadmin`
   - Create a new database named `eklaro_db`
   - Import the `database/schema.sql` file

### 2. Configuration

1. The configuration file is already created at `config/config.php`

2. Update database credentials if needed (default settings work with XAMPP):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'eklaro_db');
   ```

3. (Optional) Add your Google Fact Check API Key:
   - Get an API key from [Google Cloud Console](https://console.cloud.google.com/)
   - Enable the "Fact Check Tools API"
   - Add the key to `config/config.php`:
     ```php
     define('GOOGLE_FACT_CHECK_API_KEY', 'your-api-key-here');
     ```

### 3. File Permissions

Ensure the uploads directory is writable:

```bash
chmod 755 /opt/lampp/htdocs/Eklaro/uploads
```

### 4. Start Apache Server

```bash
sudo /opt/lampp/lampp startapache
```

### 5. Access the Application

Open your web browser and navigate to:
```
http://localhost/Eklaro
```

## Default Login Credentials

### Admin Account
- **Email:** admin@eklaro.com
- **Password:** admin123

### Test User Account
- **Email:** user@eklaro.com
- **Password:** user123

**⚠️ IMPORTANT:** Change these passwords immediately in production!

## Testing the Installation

1. **Homepage:** Visit `http://localhost/Eklaro` - You should see the landing page
2. **Login:** Click "Login" and use the admin credentials
3. **Validate Article:** Go to "Validate Article" and test with sample text
4. **Admin Panel:** Access `http://localhost/Eklaro/admin` with admin account

## Troubleshooting

### Database Connection Error
- Verify MySQL is running: `sudo /opt/lampp/lampp status`
- Check database credentials in `config/config.php`
- Ensure `eklaro_db` database exists

### Permission Denied Errors
- Check file permissions: `ls -la /opt/lampp/htdocs/Eklaro`
- Ensure Apache has read access to all files
- Ensure uploads directory is writable

### 404 Errors
- Verify `.htaccess` file exists in the root directory
- Check if `mod_rewrite` is enabled in Apache
- Ensure `AllowOverride All` is set in Apache config

### Blank Page or PHP Errors
- Check PHP error logs: `/opt/lampp/logs/php_error_log`
- Verify PHP version: `php -v` (should be 7.4+)
- Enable error display in `config/config.php` for debugging

## Optional: Google Fact Check API Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable "Fact Check Tools API"
4. Create credentials (API Key)
5. Add the API key to `config/config.php`

**Note:** The application works without the API key, but fact-checking features will be limited.

## Security Recommendations

For production deployment:

1. **Change Default Passwords**
   - Login as admin and change the password
   - Delete or disable test accounts

2. **Update Configuration**
   - Set `APP_ENV` to `'production'` in `config/config.php`
   - Disable error display

3. **Enable HTTPS**
   - Uncomment HTTPS redirect in `.htaccess`
   - Install SSL certificate

4. **Database Security**
   - Create a dedicated MySQL user with limited privileges
   - Use a strong database password

5. **File Permissions**
   - Restrict write permissions to only necessary directories
   - Set `config/config.php` to read-only (chmod 444)

## Updating

To update Eklaro:

1. Backup your database:
   ```bash
   mysqldump -u root -p eklaro_db > backup.sql
   ```

2. Backup your `config/config.php` file

3. Replace files with new version

4. Restore your configuration

5. Run any new database migrations

## Support

For issues or questions:
- Email: support@eklaro.com
- GitHub Issues: [Create an issue](https://github.com/eklaro/eklaro)
- Documentation: See README.md

## Next Steps

After installation:

1. Explore the dashboard
2. Test article validation
3. Review admin panel features
4. Customize settings as needed
5. Add your own branding (optional)

Enjoy using Eklaro! 🎉
