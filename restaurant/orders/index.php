<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

// add order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableId = (int)($_POST['table_id'] ?? 0);
    $items = $_POST['items'] ?? [];
    if ($tableId && $items) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO orders (table_id, waiter_id, status, created_at) VALUES (?, ?, "new", NOW())');
        $stmt->execute([$tableId, $_SESSION['user_id']]);
        $orderId = $pdo->lastInsertId();
        $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)');
        foreach ($items as $itemId => $qty) {
            if ($qty > 0) {
                $stmtItem->execute([$orderId, $itemId, $qty]);
            }
        }
        $pdo->prepare('UPDATE tables SET status="occupied" WHERE id=?')->execute([$tableId]);
        $pdo->commit();
    }
}

$tables = $pdo->query('SELECT id,name FROM tables WHERE status="available" ORDER BY name')->fetchAll();
$menu = $pdo->query('SELECT id,name,price FROM menu_items ORDER BY name')->fetchAll();
$orders = $pdo->query('SELECT o.id,t.name AS table_name,o.status,o.created_at FROM orders o JOIN tables t ON o.table_id=t.id WHERE o.status!="paid" ORDER BY o.created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Orders</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h5>New Order</h5>
        <form method="post">
            <div class="row">
                <div class="input-field col s4">
                    <select name="table_id" required>
                        <option value="" disabled selected>Select Table</option>
                        <?php foreach ($tables as $t): ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Table</label>
                </div>
            </div>
            <table class="striped">
                <thead><tr><th>Item</th><th>Qty</th></tr></thead>
                <tbody>
                    <?php foreach ($menu as $m): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                        <td><input type="number" name="items[<?php echo $m['id']; ?>]" min="0" value="0" style="width:60px"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn waves-effect waves-light" type="submit">Place Order</button>
        </form>
        <h5 class="section">Open Orders</h5>
        <table class="striped">
            <thead><tr><th>ID</th><th>Table</th><th>Status</th><th>Time</th></tr></thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['table_name']); ?></td>
                    <td><?php echo htmlspecialchars($o['status']); ?></td>
                    <td><?php echo $o['created_at']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded',function(){M.FormSelect.init(document.querySelectorAll('select'));});</script>
</body>
</html>
