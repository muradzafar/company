<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

if ($_SESSION['role'] !== 'kitchen' && $_SESSION['role'] !== 'admin') {
    die('Access denied');
}

if (isset($_GET['start'])) {
    $id = (int)$_GET['start'];
    $pdo->prepare('UPDATE orders SET status="preparing" WHERE id=?')->execute([$id]);
    header('Location: index.php');
    exit();
}
if (isset($_GET['ready'])) {
    $id = (int)$_GET['ready'];
    $pdo->prepare('UPDATE orders SET status="ready" WHERE id=?')->execute([$id]);
    header('Location: index.php');
    exit();
}

$orders = $pdo->query('SELECT o.id,t.name AS table_name,o.status FROM orders o JOIN tables t ON o.table_id=t.id WHERE o.status IN ("new","preparing") ORDER BY o.created_at')->fetchAll();
$itemsStmt = $pdo->prepare('SELECT m.name, i.quantity FROM order_items i JOIN menu_items m ON i.menu_item_id=m.id WHERE i.order_id=?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Kitchen</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php foreach ($orders as $o): ?>
        <div class="card blue-grey lighten-5">
            <div class="card-content">
                <span class="card-title">Order #<?php echo $o['id']; ?> - Table <?php echo htmlspecialchars($o['table_name']); ?></span>
                <ul>
                    <?php
                    $itemsStmt->execute([$o['id']]);
                    foreach ($itemsStmt->fetchAll() as $it): ?>
                        <li><?php echo htmlspecialchars($it['name']); ?> x <?php echo $it['quantity']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-action">
                <?php if ($o['status'] === 'new'): ?>
                <a href="?start=<?php echo $o['id']; ?>">Start Preparing</a>
                <?php elseif ($o['status'] === 'preparing'): ?>
                <a href="?ready=<?php echo $o['id']; ?>">Mark Ready</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
