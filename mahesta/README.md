# Mahesta

This directory contains a lightweight PHP skeleton for the Mahesta e-commerce project. It now includes a minimal product management module.

## Setup

1. Create a MySQL database named `mahesta` and add tables:

```sql
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. Insert an admin user:

```sql
INSERT INTO admins (username, password) VALUES ('admin', PASSWORD_HASH('secret', PASSWORD_DEFAULT));
```

3. Configure database access in `config.php`.

4. Serve the `mahesta` directory with PHP's built-in server for testing:

```bash
php -S localhost:8000 -t mahesta
```

Then visit `http://localhost:8000/mahesta` in your browser.
