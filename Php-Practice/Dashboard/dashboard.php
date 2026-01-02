<?php
// dashboard.php

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "calculator_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch today's records
$records = $conn->query("SELECT time_in, time_out FROM attendance WHERE date=CURDATE() ORDER BY id DESC");

// Count stats
$totalIn  = $conn->query("SELECT COUNT(*) AS cnt FROM attendance WHERE date=CURDATE() AND time_in IS NOT NULL")->fetch_assoc()['cnt'];
$totalOut = $conn->query("SELECT COUNT(*) AS cnt FROM attendance WHERE date=CURDATE() AND time_out IS NOT NULL")->fetch_assoc()['cnt'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Document</title>
</head>

<body>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Attendance Dashboard</title>
        <script>
            // Live clock
            function updateClock() {
                const now = new Date();
                const options = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                document.getElementById('clock').textContent = now.toLocaleTimeString([], options);
            }
            setInterval(updateClock, 1000);
            window.onload = updateClock;
        </script>
    </head>

    <body>
        <!-- Navigation Menu -->
        <div class="navbar">
            <a href="#">Home</a>
            <a href="time.php">Time</a>
            <a href="#">Profile</a>
            <a href="#">About</a>
            <a href="#">Contact Us</a>
            <a href="#">More</a>
        </div>

        <div class="header">
            <h1>Attendance Dashboard</h1>
            <p>📅 Today: <strong><?= date("l, F j, Y") ?></strong> | ⏰ <span id="clock"></span></p>
        </div>

        <div class="container">
            <div class="card">
                <h2>Total Time Ins</h2>
                <p><?= $totalIn ?></p>
            </div>
            <div class="card">
                <h2>Total Time Outs</h2>
                <p><?= $totalOut ?></p>
            </div>
        </div>

        <div class="container">
            <div class="card" style="flex: 1 1 100%;">
                <h2>Today's Attendance Log</h2>
                <table>
                    <tr>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                    <?php while ($row = $records->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['time_in'] ?></td>
                            <td><?= $row['time_out'] ?: '-' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </body>

    </html>