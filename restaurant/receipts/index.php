<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

if (isset($_GET['pay'])) {
    $id = (int)$_GET['pay'];
    $pdo->prepare('UPDATE orders SET status="paid" WHERE id=?')->execute([$id]);
    $pdo->prepare('UPDATE tables SET status="available" WHERE id=(SELECT table_id FROM orders WHERE id=?)')->execute([$id]);
    header('Location: index.php');
    exit();
}

$orders = $pdo->query('SELECT o.id,t.name AS table_name,o.created_at FROM orders o JOIN tables t ON o.table_id=t.id WHERE o.status="ready" ORDER BY o.created_at')->fetchAll();
$itemsStmt = $pdo->prepare('SELECT m.name,i.quantity,m.price FROM order_items i JOIN menu_items m ON i.menu_item_id=m.id WHERE i.order_id=?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Receipts</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php foreach ($orders as $o): ?>
        <div class="card">
            <div class="card-content">
                <span class="card-title">Receipt for Order #<?php echo $o['id']; ?></span>
                <p>Table: <?php echo htmlspecialchars($o['table_name']); ?></p>
                <ul>
                    <?php
                    $itemsStmt->execute([$o['id']]);
                    $total=0;
                    foreach ($itemsStmt->fetchAll() as $it):
                        $line=$it['price']*$it['quantity'];
                        $total+=$line; ?>
                        <li><?php echo htmlspecialchars($it['name']); ?> x <?php echo $it['quantity']; ?> - $<?php echo number_format($line,2); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>Total: $<?php echo number_format($total,2); ?></p>
            </div>
            <div class="card-action">
                <a href="?pay=<?php echo $o['id']; ?>">Mark Paid</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
