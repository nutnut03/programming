<?php
// time_tracker.php

// Database connection
$servername = "localhost";
$username   = "root";   // change if needed
$password   = "";       // change if needed
$dbname     = "calculator_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = htmlspecialchars($_POST['name']);
    $action = $_POST['action']; // "in" or "out"
    $date   = date("Y-m-d");
    $time   = date("Y-m-d H:i:s");

    if ($action == "in") {
        // Insert new record for time in
        $stmt = $conn->prepare("INSERT INTO attendance (name, time_in, date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $time, $date);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Time In recorded for $name at $time";
    } elseif ($action == "out") {
        // Update latest record for this user (same date)
        $stmt = $conn->prepare("UPDATE attendance 
                                SET time_out=? 
                                WHERE name=? AND date=? AND time_out IS NULL 
                                ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("sss", $time, $name, $date);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Time Out recorded for $name at $time";
    }
}

// Fetch today's records
$records = $conn->query("SELECT name, time_in, time_out FROM attendance WHERE date='$currentDate' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Time In/Out Tracker</title>
    <link rel="stylesheet" href="time.css">

</head>

<body>
    <h2>Employee Time Tracker</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Enter your name" required>
        <button type="submit" name="action" value="in">Time In</button>
        <button type="submit" name="action" value="out">Time Out</button>
    </form>

    <?php if ($message !== ""): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
</body>

</html>