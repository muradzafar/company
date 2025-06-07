# Company Project
این ریپازیتوری برای پروژه شرکت می‌باشد.

## Expense Manager
A simple PHP/MySQL income and expense manager is available in the `expense_manager` directory. It includes registration, login, a dashboard, and basic transaction management.

### Database Schema
```
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('income','expense') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

Create a MySQL database named `expense_manager` and import the schema above to get started.
