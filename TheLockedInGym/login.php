<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username_email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :ue OR email = :ue");
        $stmt->execute(['ue' => $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid username/email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - The Locked In Gym</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="form-box">
        <h1>Login</h1>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <div class="inputbox">
                <input type="text" name="username_email" required="required">
                <span> Username or Email</span>
                <i></i>
            </div>

            <div class="inputbox">
                <input type="password" name="password" required="required">
                <span>Password</span>
                <i></i>
            </div>

            <button type="submit">Login</button>
        </form>

        <p><a href="register.php">Register</a> if you don't have an account.</p>
</body>

</html>