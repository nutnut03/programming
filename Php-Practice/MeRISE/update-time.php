<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Manila");

// Database connection
$servername = "localhost";
$username   = "root";   // change if needed
$password   = "";       // change if needed
$dbname     = "MeRISE_DB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// ✅ Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = $_POST['id']; // record ID
    $time_in  = $_POST['time_in'];
    $time_out = $_POST['time_out'];

    $stmt = $conn->prepare("UPDATE attendance SET time_in=?, time_out=? WHERE id=?");
    $stmt->bind_param("ssi", $time_in, $time_out, $id);
    if ($stmt->execute()) {
        $message = "✅ Attendance record updated successfully!";
    } else {
        $message = "❌ Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// ✅ Fetch ALL records (not just today)
$records = $conn->query("SELECT id, date, time_in, time_out 
                         FROM attendance 
                         ORDER BY date DESC, id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Attendance</title>
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

    <h1>Update Attendance Records</h1>
    <?php if ($message !== ""): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $records->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="">
                    <td><?= $row['id'] ?>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    </td>
                    <td><?= $row['date'] ?></td>
                    <!-- ✅ Show plain text instead of input box -->
                    <td><?= $row['time_in'] ?></td>
                    <td><?= $row['time_out'] ?></td>
                    <td><button type="submit">Update</button></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

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
</body>

</html>