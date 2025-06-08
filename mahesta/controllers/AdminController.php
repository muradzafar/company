<?php
use Core\Auth;

class AdminController
{
    public function login(): void
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            if (Auth::attempt($username, $password)) {
                header('Location: ' . Auth::baseUrl('/admin'));
                exit();
            } else {
                $error = 'Invalid credentials';
            }
        }
        $title = 'Admin Login';
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../views/admin/login.php';
        require __DIR__ . '/../templates/footer.php';
    }

    public function index(): void
    {
        Auth::requireLogin();
        $pdo = Core\Database::connection();
        $productCount = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        $title = 'Admin Dashboard';
        require __DIR__ . '/../templates/header.php';
        echo '<div class="container py-5">';
        echo '<h1 class="mb-4">Admin Dashboard</h1>';
        echo '<div class="alert alert-info">Total products: ' . $productCount . '</div>';
        echo '</div>';
        require __DIR__ . '/../templates/footer.php';
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: ' . Auth::baseUrl('/admin/login'));
        exit();
    }
}
