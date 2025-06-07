<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$totalIncome = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'income'");
$totalIncome->execute([$userId]);
$income = $totalIncome->fetchColumn() ?: 0;

$totalExpense = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'expense'");
$totalExpense->execute([$userId]);
$expense = $totalExpense->fetchColumn() ?: 0;

$balance = $income - $expense;

$recent = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC LIMIT 5");
$recent->execute([$userId]);
$transactions = $recent->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h2>Dashboard</h2>
<p>Total Income: <?php echo number_format($income, 2); ?></p>
<p>Total Expense: <?php echo number_format($expense, 2); ?></p>
<p>Current Balance: <?php echo number_format($balance, 2); ?></p>
<a class="btn btn-primary mb-3" href="add_transaction.php">Add Transaction</a>
<a class="btn btn-secondary mb-3" href="transactions.php">View All</a>
<a class="btn btn-link" href="logout.php">Logout</a>
<h4>Recent Transactions</h4>
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
</body>
</html>
