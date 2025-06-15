<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

$dateFrom = $_GET['from'] ?? date('Y-m-01');
$dateTo = $_GET['to'] ?? date('Y-m-d');
$stmt = $pdo->prepare('SELECT o.id, SUM(m.price*i.quantity) AS total FROM orders o JOIN order_items i ON o.id=i.order_id JOIN menu_items m ON i.menu_item_id=m.id WHERE o.status="paid" AND DATE(o.created_at) BETWEEN ? AND ? GROUP BY o.id');
$stmt->execute([$dateFrom, $dateTo]);
$sales = $stmt->fetchAll();
$total = array_sum(array_column($sales, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Sales</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <form method="get" class="row">
            <div class="input-field col s4">
                <input type="date" name="from" id="from" value="<?php echo $dateFrom; ?>">
                <label for="from">From</label>
            </div>
            <div class="input-field col s4">
                <input type="date" name="to" id="to" value="<?php echo $dateTo; ?>">
                <label for="to">To</label>
            </div>
            <div class="input-field col s4">
                <button class="btn waves-effect waves-light" type="submit">Filter</button>
            </div>
        </form>
        <table class="striped">
            <thead><tr><th>Order ID</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo $s['id']; ?></td>
                    <td>$<?php echo number_format($s['total'],2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="grey lighten-4">
                    <td><strong>Total Revenue</strong></td>
                    <td><strong>$<?php echo number_format($total,2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
