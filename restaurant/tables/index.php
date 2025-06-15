<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name) {
        $stmt = $pdo->prepare('INSERT INTO tables (name, status) VALUES (?, "available")');
        $stmt->execute([$name]);
    }
}

if (isset($_GET['free'])) {
    $id = (int)$_GET['free'];
    $stmt = $pdo->prepare('UPDATE tables SET status="available" WHERE id=?');
    $stmt->execute([$id]);
    header('Location: index.php');
    exit();
}

$tables = $pdo->query('SELECT * FROM tables ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Tables</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h5>Add Table</h5>
        <form method="post">
            <div class="row">
                <div class="input-field col s8">
                    <input id="name" name="name" type="text" required>
                    <label for="name">Table Name</label>
                </div>
                <div class="input-field col s4">
                    <button class="btn waves-effect waves-light" type="submit">Add</button>
                </div>
            </div>
        </form>
        <h5>Existing Tables</h5>
        <table class="striped">
            <thead><tr><th>Name</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($tables as $t): ?>
                <tr>
                    <td><?php echo htmlspecialchars($t['name']); ?></td>
                    <td><?php echo htmlspecialchars($t['status']); ?></td>
                    <td>
                        <?php if ($t['status'] !== 'available'): ?>
                        <a href="?free=<?php echo $t['id']; ?>">Mark Available</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
