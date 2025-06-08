<?php
use Core\Auth;

class HomeController
{
    public function index(): void
    {
        $products = Product::all();
        $title = 'Welcome to Mahesta';
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../views/home.php';
        require __DIR__ . '/../templates/footer.php';
    }
}
