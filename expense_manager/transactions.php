<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$where = 'WHERE user_id = ?';
$params = [$userId];

if (!empty($_GET['type'])) {
    $where .= ' AND type = ?';
    $params[] = $_GET['type'];
}

if (!empty($_GET['category'])) {
    $where .= ' AND category = ?';
    $params[] = $_GET['category'];
}

if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $where .= ' AND date BETWEEN ? AND ?';
    $params[] = $_GET['from'];
    $params[] = $_GET['to'];
}

$query = $pdo->prepare("SELECT * FROM transactions $where ORDER BY date DESC");
$query->execute($params);
$transactions = $query->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h2>Transactions</h2>
<form method="get" class="mb-3">
    <div class="form-row">
        <div class="col">
            <input type="date" name="from" class="form-control" placeholder="From" value="<?php echo htmlspecialchars($_GET['from'] ?? '') ?>">
        </div>
        <div class="col">
            <input type="date" name="to" class="form-control" placeholder="To" value="<?php echo htmlspecialchars($_GET['to'] ?? '') ?>">
        </div>
        <div class="col">
            <input type="text" name="category" class="form-control" placeholder="Category" value="<?php echo htmlspecialchars($_GET['category'] ?? '') ?>">
        </div>
        <div class="col">
            <select name="type" class="form-control">
                <option value="">All</option>
                <option value="income" <?php if (($_GET['type'] ?? '') === 'income') echo 'selected'; ?>>Income</option>
                <option value="expense" <?php if (($_GET['type'] ?? '') === 'expense') echo 'selected'; ?>>Expense</option>
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?php echo htmlspecialchars($t['date']); ?></td>
            <td><?php echo htmlspecialchars($t['type']); ?></td>
            <td><?php echo htmlspecialchars($t['category']); ?></td>
            <td><?php echo number_format($t['amount'], 2); ?></td>
            <td><?php echo htmlspecialchars($t['description']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="dashboard.php" class="btn btn-secondary">Back</a>
</body>
</html>
