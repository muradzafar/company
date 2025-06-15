<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="#" class="brand-logo center">Dashboard</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="../menu">Menu</a></li>
                <li><a href="../orders">Orders</a></li>
                <li><a href="../kds">KDS</a></li>
                <li><a href="../tables">Tables</a></li>
                <li><a href="../staff">Staff</a></li>
                <li><a href="../sales">Sales</a></li>
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h4>Welcome!</h4>
        <p>This is a minimal dashboard placeholder.</p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
