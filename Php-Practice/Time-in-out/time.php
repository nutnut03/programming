<?php
// time_tracker.php

// Database connection
$servername = "localhost";
$username   = "root";   // change if needed
$password   = "";       // change if needed
$dbname     = "MeRISE_DB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$currentDate = date("l, F j, Y"); // Example: Friday, December 19, 2025

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // "in" or "out"
    $date   = date("Y-m-d");
    $time   = date("Y-m-d H:i:s");

    if ($action == "in") {
        // Insert new record for time in only if not already recorded today
        $check = $conn->query("SELECT id FROM attendance WHERE date='$date' AND time_in IS NOT NULL");
        if ($check->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO attendance (time_in, date) VALUES (?, ?)");
            $stmt->bind_param("ss", $time, $date);
            $stmt->execute();
            $stmt->close();
            $message = "✅ Time In recorded at $time";
        } else {
            $message = "⚠️ Time In already recorded today.";
        }
    } elseif ($action == "out") {
        // Update latest record for today
        $stmt = $conn->prepare("UPDATE attendance 
                                SET time_out=? 
                                WHERE date=? AND time_out IS NULL 
                                ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("ss", $time, $date);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Time Out recorded at $time";
    }
}

// Fetch today's records
$records = $conn->query("SELECT time_in, time_out FROM attendance WHERE date=CURDATE() ORDER BY id DESC");

// Check if Time In already exists today
$timeInExists = $conn->query("SELECT id FROM attendance WHERE date=CURDATE() AND time_in IS NOT NULL")->num_rows > 0;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Time In/Out Tracker</title>
    <link rel="stylesheet" href="time.css">
    <script>
        // Live clock that updates every second
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
    <div class="container">
        <h2>Time In/Out Tracker</h2>
        <p>📅 Today’s Date: <strong><?= $currentDate ?></strong></p>
        <p>⏰ Current Time: <strong id="clock"></strong></p>

        <form method="POST" action="">
            <?php if (!$timeInExists): ?>
                <button type="submit" name="action" value="in">Time In</button>
            <?php endif; ?>
            <button type="submit" name="action" value="out">Time Out</button>
        </form>

        <?php if ($message !== ""): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <h3>Today's Attendance Log</h3>
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
</body>

</html>