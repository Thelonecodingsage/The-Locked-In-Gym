<?php

$host = "localhost";
$user = "YOUR_DB_USER";
$password = "YOUR_PASS";
$dbname = "YOUR_DBNAME";
$port = 8889;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>