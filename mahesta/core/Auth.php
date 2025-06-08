<?php
namespace Core;

class Auth
{
    public static function check(): bool
    {
        session_start();
        return !empty($_SESSION['admin_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . self::baseUrl('/admin/login'));
            exit();
        }
    }

    public static function attempt(string $username, string $password): bool
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            return true;
        }
        return false;
    }

    public static function logout(): void
    {
        session_start();
        session_destroy();
    }

    public static function baseUrl(string $path = ''): string
    {
        $config = require __DIR__ . '/../config.php';
        return rtrim($config['base_url'], '/') . $path;
    }
}
