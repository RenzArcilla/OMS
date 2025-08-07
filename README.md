# Storage and Output Monitoring System

This repository will serve as the code base version control repo for the Storage and Output Monitoring System for machines and applicators...

---

## Setup Guide 

This guide will help you set up the SOMS app on your local machine.

---

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/SOMS.git
cd SOMS
Replace your-username with your actual GitHub username.
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

### 3. Run the App
Start your local server (e.g., XAMPP) and navigate to:
```bash
http://localhost/SOMS/public/index.php
```

---

## Notes
If you encounter any issues, check that all required PHP extensions are enabled in php.ini (ensure PHP extensions ext-gd and ext-zip are enabled).