# Online Restaurant Management System

This folder contains a lightweight PHP implementation of an online restaurant management system. It demonstrates key modules such as user login, menu and table management, order tracking and a simple kitchen display.

## Database Schema

Create a MySQL database named `restaurant` and import the following tables:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

CREATE TABLE tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'available'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_id INT NOT NULL,
    waiter_id INT NOT NULL,
    status VARCHAR(50) DEFAULT 'new',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL
);
```

## Local Usage

1. Update database credentials in `includes/db.php`.
2. Run PHP's built in server from the repository root:

```bash
php -S localhost:8000 -t restaurant
```

3. Open `http://localhost:8000/restaurant` in your browser.

## Deploying on Hostinger

1. Log in to Hostinger's hPanel and create a new MySQL database. Note the database name, user and password.
2. Upload the `restaurant` directory to your hosting space using the File Manager or via FTP.
3. Edit `restaurant/includes/db.php` with the database credentials provided by Hostinger.
4. Using phpMyAdmin in hPanel, import the SQL schema above to create all required tables.
5. Visit your domain followed by `/restaurant` to access the application. (Example: `https://yourdomain.com/restaurant`)

This project is intentionally minimal to showcase structure. Feel free to extend the pages under `staff`, `tables`, `orders`, `kds`, `receipts` and `sales` to fit your needs.
