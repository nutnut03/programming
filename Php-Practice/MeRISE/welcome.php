<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
date_default_timezone_set("Asia/Manila");

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = "";
$today = date("Y-m-d");
$time = date("Y-m-d H:i:s");

if ($_POST) {
    if ($_POST['action'] == "in") {
        if ($conn->query("SELECT id FROM attendance WHERE date='$today' AND time_in IS NOT NULL")->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO attendance(time_in,date) VALUES(?,?)");
            $stmt->bind_param("ss", $time, $today);
            $stmt->execute();
            $stmt->close();
            $message = "✅ Time In recorded at $time";
        } else $message = "⚠️ Time In already recorded today.";
    } else {
        $stmt = $conn->prepare("UPDATE attendance SET time_out=? WHERE date=? AND time_out IS NULL ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("ss", $time, $today);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Time Out recorded at $time";
    }
}
$records = $conn->query("SELECT time_in,time_out FROM attendance WHERE date='$today' ORDER BY id DESC");
$timeInExists = $conn->query("SELECT id FROM attendance WHERE date='$today' AND time_in IS NOT NULL")->num_rows > 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="merise-styles.css">
    <script>
        function updateClockAndGreeting() {
            const now = new Date(),
                h = now.getHours(),
                m = String(now.getMinutes()).padStart(2, '0'),
                s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById("clock").textContent = `Current Time: ${h}:${m}:${s}`;
            document.getElementById("greeting").textContent = h < 12 ? "Good Morning ☀️" : h < 18 ? "Good Afternoon 🌤️" : "Good Evening 🌙";
        }
        setInterval(updateClockAndGreeting, 1000);
        window.onload = updateClockAndGreeting;
    </script>
</head>

<body>
    <nav class="nav">
        <div class="logo"><a href="https://www.facebook.com/MeRISEEnglishAcademyCebu"><img src="images/MeRISE-png.png"></a></div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a><a href="welcome.php">Welcome</a>
            <div class="dropdown"><a href="#" class="dropbtn">Attendance</a>
                <div class="dropdown-content">
                    <a href="attendance-logs.php">View Logs</a><a href="update-time.php">Update Attendance</a><a href="delete-time.php">Delete Attendance</a>
                </div>
            </div>
            <div class="dropdown"><a href="#" class="dropbtn">Request</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit Request</a><a href="view-requests.php">View My Requests</a>
                </div>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="welcome-container">
        <div class="welcome-box">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 🎉</h1>
            <p id="greeting"></p>
            <p id="clock"></p>
            <form method="POST"><?php if (!$timeInExists): ?><button name="action" value="in">Time In</button><?php endif; ?>
                <button name="action" value="out">Time Out</button>
            </form>
            <?php if ($message): ?><div class="message"><?= $message ?></div><?php endif; ?>
            <h3>Today's Attendance Log</h3>
            <table>
                <tr>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
                <?php while ($r = $records->fetch_assoc()): ?><tr>
                        <td><?= $r['time_in'] ?></td>
                        <td><?= $r['time_out'] ?: '-' ?></td>
                    </tr><?php endwhile; ?>
            </table>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <h3>MeRISE English Academy</h3>
            <p>ESY Building, Corner Juana Osmeña St., Brgy. Kamputhaw, Cebu City</p>
            <p><strong>Tel. (PLDT):</strong> (032) 345 8524 <br><strong>Tel.