<?php
use Core\Database;

class Category
{
    public static function all(): array
    {
        $pdo = Database::connection();
        return $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
    }
}
