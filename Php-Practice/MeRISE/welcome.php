<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="merise-styles.css">
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
    <!-- Navigation -->
    <nav class="nav">
        <div class="logo"><a href="https://www.facebook.com/MeRISEEnglishAcademyCebu"><img src="images/MeRISE-png.png"></a></div>
        <div class=" menu-toggle" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a>
            <a href="welcome.php">Welcome</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>


    <!-- ✅ Responsive Welcome Box -->
    <div class="welcome-container">
        <div class="welcome-box">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 🎉</h1>
            <p id="greeting"></p>
            <p id="clock"></p>
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
    </div>

    <!-- ✅ JavaScript for Dynamic Greeting & Clock -->
    <script>
        function updateClockAndGreeting() {
            const clock = document.getElementById("clock");
            const greeting = document.getElementById("greeting");

            const now = new Date();
            const hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // ⏰ Display current time
            clock.textContent = `Current Time: ${hours}:${minutes}:${seconds}`;

            // 👋 Set dynamic greeting
            let message = "";
            if (hours < 12) {
                message = "Good Morning ☀️";
            } else if (hours < 18) {
                message = "Good Afternoon 🌤️";
            } else {
                message = "Good Evening 🌙";
            }
            greeting.textContent = message;
        }

        // Update every second
        setInterval(updateClockAndGreeting, 1000);
        updateClockAndGreeting();
    </script>



    <!-- 📚 Library Book Gallery 
    <div class="library-container">
        <h2 class="library-title">📚 MeRISE E-Library</h2>

        <div class="library-grid">
            <div class="book-card">
                <img src="images/gadget.jpg" alt="Business English">
                <div class="book-info">
                    <h3>Business English</h3>
                    <p>Master communication in the corporate world.</p>
                </div>
            </div>

            <div class="book-card">
                <img src="images/gadget.jpg" alt="Pronunciation Practice">
                <div class="book-info">
                    <h3>Pronunciation Practice</h3>
                    <p>Enhance your accent and speaking clarity.</p>
                </div>
            </div>

            <div class="book-card">
                <img src="images/gadget.jpg" alt="General English">
                <div class="book-info">
                    <h3>General English</h3>
                    <p>Improve everyday English skills effectively.</p>
                </div>
            </div>

            <div class="book-card">
                <img src="images/gadget.jpg" alt="Vocabulary Builder">
                <div class="book-info">
                    <h3>Vocabulary Builder</h3>
                    <p>Learn new words with fun exercises.</p>
                </div>
            </div>

            <div class="book-card">
                <img src="images/gadget.jpg" alt="Conversation Practice">
                <div class="book-info">
                    <h3>Conversation Practice</h3>
                    <p>Boost your confidence in speaking sessions.</p>
                </div>
            </div>

            <div class="book-card">
                <img src="images/gadget.jpg" alt="Business Writing">
                <div class="book-info">
                    <h3>Business Writing</h3>
                    <p>Write professional emails and reports with ease.</p>
                </div>
            </div>
            <div class="book-card">
                <img src="images/gadget.jpg" alt="Business Writing">
                <div class="book-info">
                    <h3>Speech Pattern</h3>
                    <p>Write professional emails and reports with ease.</p>
                </div>
            </div>
            <div class="book-card">
                <img src="images/gadget.jpg" alt="Business Writing">
                <div class="book-info">
                    <h3>Grammar Writing</h3>
                    <p>Write professional emails and reports with ease.</p>
                </div>
            </div>
        </div>
    </div> -->

    <!-- ✅ Responsive Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <h3>MeRISE English Academy</h3>
                <p>
                    ESY Building, Corner Juana Osmeña St., Brgy. Kamputhaw, Cebu City
                </p>
                <p>
                    <strong>Tel. (PLDT):</strong> (032) 345 8524 <br>
                    <strong>Tel. (Globe):</strong> (032) 479 0414
                </p>
                <p>
                    <strong>Email:</strong>
                    <a href="mailto:academic_support@meriseinc.com">
                        academic_support@meriseinc.com
                    </a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 MeRISE English Academy. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
</body>

</html>