<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Member';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $day = $_POST['day_of_week'];
    $time = $_POST['time_slot'];
    $activity = $_POST['activity'];

    try {
        $stmt = $pdo->prepare("INSERT INTO schedules (user_id, day_of_week, time_slot, activity) VALUES (:uid, :day, :time, :activity)");
        $stmt->execute([
            'uid' => $user_id,
            'day' => $day,
            'time' => $time,
            'activity' => $activity
        ]);
        $message = "Schedule booked successfully!";
    } catch (PDOException $e) {
        $message = "Error booking schedule: " . $e->getMessage();
    }
}

$schedule_data = [];
try {
    $stmt = $pdo->prepare("SELECT day_of_week, time_slot, activity FROM schedules WHERE user_id = :uid ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), time_slot");
    $stmt->execute(['uid' => $user_id]);
    $schedule_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - The Locked In Gym</title>
    <link rel="stylesheet" href="assets/style.css">
    </head>
<body>
    <div class="form-box" style="max-width: 600px;">
        <h1>Member Dashboard</h1>
        <p>Welcome back, <?= htmlspecialchars($username) ?>!</p>
        <p style="color: #45f3ff;"><?= htmlspecialchars($message) ?></p>

        <h2>Your Current Schedule</h2>
        <?php if (count($schedule_data) > 0): ?>
            <table style="width: 100%; color: white; border-collapse: collapse; margin-bottom: 30px;">
                <thead>
                    <tr style="background: #a96f9f;">
                        <th style="padding: 8px; border: 1px solid #45f3ff;">Day</th>
                        <th style="padding: 8px; border: 1px solid #45f3ff;">Time</th>
                        <th style="padding: 8px; border: 1px solid #45f3ff;">Activity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedule_data as $row): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #555;"><?= htmlspecialchars($row['day_of_week']) ?></td>
                        <td style="padding: 8px; border: 1px solid #555;"><?= htmlspecialchars($row['time_slot']) ?></td>
                        <td style="padding: 8px; border: 1px solid #555;"><?= htmlspecialchars($row['activity']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #f0f0f0;">You have no scheduled activities yet.</p>
        <?php endif; ?>

        <hr style="width: 100%; border-color: #555; margin: 30px 0;">

        <h2>Book a Slot</h2>
        <form method="post" action="" style="width: 100%;">
            <div class="inputbox">
                <select name="day_of_week" required style="width: 100%; padding: 10px; margin-bottom: 20px; background: #333; color: white;">
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            
            <div class="inputbox">
                <input type="text" name="time_slot" required>
                <span>Time Slot (e.g., 9:00 AM)</span>
                <i></i>
            </div>
            
            <div class="inputbox">    
                <input type="text" name="activity" required>
                <span>Activity (e.g., Yoga, Weights, Trainer Session)</span>
                <i></i>
            </div>

            <button type="submit" name="book">Book Schedule</button>
        </form>

        <p style="margin-top: 30px;"><a href="logout.php">Logout</a> | <a href="index.php">Home</a></p>
    </div>
</body>
</html>