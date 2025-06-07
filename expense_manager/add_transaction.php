<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    $date = $_POST['date'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($type && $amount && $category && $date) {
        $stmt = $pdo->prepare('INSERT INTO transactions (user_id, type, amount, category, date, description) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $type, $amount, $category, $date, $description]);
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Please fill all required fields';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="container py-5">
<h2>Add Transaction</h2>
<?php if (!empty($error)) : ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="post">
    <div class="form-group">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="">Select Type</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <div class="form-group">
        <label>Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>
