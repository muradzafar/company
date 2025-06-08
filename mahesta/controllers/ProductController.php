<?php
use Core\Auth;

class ProductController
{
    public function index(): void
    {
        Auth::requireLogin();
        $products = Product::all();
        $title = 'Products';
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../views/admin/products/index.php';
        require __DIR__ . '/../templates/footer.php';
    }

    public function create(): void
    {
        Auth::requireLogin();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titleField = trim($_POST['title'] ?? '');
            $priceField = floatval($_POST['price'] ?? 0);
            if ($titleField && $priceField) {
                Product::create(['title' => $titleField, 'price' => $priceField]);
                header('Location: ' . Auth::baseUrl('/admin/products'));
                exit();
            } else {
                $error = 'Please fill all fields';
            }
        }
        $title = 'Add Product';
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../views/admin/products/create.php';
        require __DIR__ . '/../templates/footer.php';
    }
}
