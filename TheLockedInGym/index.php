<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? $_SESSION['username'] : null;
$role = $loggedIn ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>The Locked In Gym</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <h1>Welcome to The Locked In Gym</h1>

    <?php if ($loggedIn) : ?>

        <p>Hello, <?= htmlspecialchars($username ?? '') ?>!</p>

        <p>Your role: <?= htmlspecialchars($role ?? '') ?></p>

        <?php if ($role === 'admin'): ?>
            <p><a href="admin_dashboard.php">Go to Admin Dashboard</a></p>
        <?php else: ?>
            <p><a href="user_dashboard.php">Go to User Dashboard</a></p>
        <?php endif; ?>

        <p><a href="logout.php">Logout</a></p>

    <?php else: ?>
        <p><a href="register.php">Register</a> | <a href="login.php">Login</a></p>
    <?php endif; ?>
</body>

</html>