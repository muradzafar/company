<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();

if ($_SESSION['role'] !== 'admin') {
    die('Access denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? 'waiter');
    if ($name && $username && $password) {
        $stmt = $pdo->prepare('INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $username, password_hash($password, PASSWORD_BCRYPT), $role]);
    }
}

$staff = $pdo->query('SELECT id, name, username, role FROM users ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="../dashboard" class="brand-logo center">Staff</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h5>Add Staff</h5>
        <form method="post">
            <div class="row">
                <div class="input-field col s3">
                    <input id="name" name="name" type="text" required>
                    <label for="name">Name</label>
                </div>
                <div class="input-field col s3">
                    <input id="username" name="username" type="text" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-field col s3">
                    <input id="password" name="password" type="password" required>
                    <label for="password">Password</label>
                </div>
                <div class="input-field col s2">
                    <select name="role">
                        <option value="waiter">Waiter</option>
                        <option value="kitchen">Kitchen</option>
                        <option value="admin">Admin</option>
                    </select>
                    <label>Role</label>
                </div>
                <div class="input-field col s1">
                    <button class="btn waves-effect waves-light" type="submit">Add</button>
                </div>
            </div>
        </form>
        <h5>Staff List</h5>
        <table class="striped">
            <thead><tr><th>Name</th><th>Username</th><th>Role</th></tr></thead>
            <tbody>
                <?php foreach ($staff as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['username']); ?></td>
                    <td><?php echo htmlspecialchars($s['role']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded',function(){var elems=document.querySelectorAll('select');M.FormSelect.init(elems);});</script>
</body>
</html>
