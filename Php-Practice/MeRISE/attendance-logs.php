<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ✅ Set timezone to Philippines
date_default_timezone_set("Asia/Manila");

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

// ✅ Fetch ALL attendance records
$records = $conn->query("SELECT id, time_in, time_out, date, created_at 
                         FROM attendance 
                         ORDER BY date DESC, id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Log</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="logo">
            <a href="https://www.facebook.com/MeRISEEnglishAcademyCebu">
                <img src="images/MeRISE-png.png" alt="MeRISE Logo">
            </a>
        </div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a>
            <a href="welcome.php">Welcome</a>

            <!-- Dropdown without arrow -->
            <div class="dropdown">
                <a href="#" class="dropbtn">Attendance</a>
                <div class="dropdown-content">
                    <a href="attendance-logs.php">View Logs</a>
                    <a href="update-time.php">Update Attendance</a>
                    <a href="delete-time.php">Delete Attendance</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#" class="dropbtn">Request</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit Request</a>
                    <a href="view-requests.php">View My Requests</a>
                </div>
            </div>


            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- ✅ Attendance Log Table -->
    <div class="attendance-container">
        <h1>📊 Full Attendance Log</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $records->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['time_in'] ?: '-' ?></td>
                    <td><?= $row['time_out'] ?: '-' ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- ✅ Responsive Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-info">
                <h3>MeRISE English Academy</h3>
                <p>ESY Building, Corner Juana Osmeña St., Brgy. Kamputhaw, Cebu City</p>
                <p><strong>Tel. (PLDT):</strong> (032) 345 8524 <br>
                    <strong>Tel. (Globe):</strong> (032) 479 0414
                </p>
                <p><strong>Email:</strong>
                    <a href="mailto:academic_support@meriseinc.com">academic_support@meriseinc.com</a>
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 MeRISE English Academy. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>