<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - The Locked In Gym</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($username) ?>!</p>

    <ul>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
