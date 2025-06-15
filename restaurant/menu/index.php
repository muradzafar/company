<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

// Handle new item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    if ($name && $price) {
        $stmt = $pdo->prepare('INSERT INTO menu_items (name, price) VALUES (?, ?)');
        $stmt->execute([$name, $price]);
    }
}

$items = $pdo->query('SELECT * FROM menu_items ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Menu</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h5>Add Item</h5>
        <form method="post">
            <div class="row">
                <div class="input-field col s6">
                    <input id="name" type="text" name="name" required>
                    <label for="name">Name</label>
                </div>
                <div class="input-field col s4">
                    <input id="price" type="number" step="0.01" name="price" required>
                    <label for="price">Price</label>
                </div>
                <div class="input-field col s2">
                    <button class="btn waves-effect waves-light" type="submit">Add</button>
                </div>
            </div>
        </form>
        <h5>Menu Items</h5>
        <table class="striped">
            <thead>
                <tr><th>Name</th><th>Price</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
