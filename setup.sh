#!/bin/bash

# Eklaro Setup Script
# This script automates the installation process

echo "========================================="
echo "  Eklaro Installation Script"
echo "========================================="
echo ""

# Check if MySQL is running
echo "Checking MySQL status..."
if ! sudo /opt/lampp/lampp status | grep -q "mysql.*running"; then
    echo "Starting MySQL..."
    sudo /opt/lampp/lampp startmysql
    sleep 2
fi

# Import database schema
echo ""
echo "Setting up database..."
mysql -u root -p -e "SOURCE /opt/lampp/htdocs/Eklaro/database/schema.sql"

if [ $? -eq 0 ]; then
    echo "✓ Database created successfully!"
else
    echo "✗ Database creation failed. Please check your MySQL credentials."
    exit 1
fi

# Set permissions
echo ""
echo "Setting file permissions..."
chmod 755 /opt/lampp/htdocs/Eklaro/uploads
chmod 644 /opt/lampp/htdocs/Eklaro/config/config.php

echo "✓ Permissions set successfully!"

# Check Apache status
echo ""
echo "Checking Apache status..."
if ! sudo /opt/lampp/lampp status | grep -q "apache.*running"; then
    echo "Starting Apache..."
    sudo /opt/lampp/lampp startapache
    sleep 2
fi

echo ""
echo "========================================="
echo "  Installation Complete!"
echo "========================================="
echo ""
echo "Access Eklaro at: http://localhost/Eklaro"
echo ""
echo "Default Login Credentials:"
echo "  Admin: admin@eklaro.com / admin123"
echo "  User:  user@eklaro.com / user123"
echo ""
echo "⚠️  Remember to change these passwords!"
echo ""
echo "For more information, see INSTALLATION.md"
echo "========================================="
