# Output Monitoring System (OMS)

This repository serves as the version-controlled codebase for the Output Monitoring System (OMS) — a web-based application for monitoring and managing machine and applicator part outputs in a production environment.

---

## Setup Guide 

Follow these steps to set up and run OMS locally using XAMPP.

---

### 1. Clone the Repository in the htdocs dir (xampp)
```bash
cd htdocs
git clone https://github.com/RenzArcilla/OMS.git
cd OMS
```

### 2. Install Dependencies (Composer)
Prerequisites:
PHP installed (>= 8.1 recommended)
Composer installed

Install PHP dependencies:
```bash
php composer.phar install
```

Or if you installed Composer globally:
```bash
composer install
```
This will install all required packages, including PhpSpreadsheet.

### 3. Setup the database
1. Open app/sql/database.sql and copy its contents.
2. Launch the XAMPP Control Panel and start Apache and MySQL.
3. Go to [phpMyAdmin](http://localhost/phpmyadmin/index.php).
4. Run the database.sql script
5. Add an admin user manually via phpMyAdmin.

### 4. Run the App
Once the server is running, access the app through your browser:
```bash
http://localhost/OMS/public/app.php
```

---

## Notes & Troubleshooting
- Ensure required PHP extensions are enabled in php.ini:
    - ext-gd
    - ext-zip
- Restart Apache after making changes.
