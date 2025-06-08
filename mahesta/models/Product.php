<?php
use Core\Database;

class Product
{
    public static function all(): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO products (title, price, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$data['title'], $data['price']]);
        return (int)$pdo->lastInsertId();
    }
}
