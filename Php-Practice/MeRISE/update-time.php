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
if ($_POST) {
    $stmt = $conn->prepare("UPDATE attendance SET time_in=?, time_out=? WHERE id=?");
    $stmt->bind_param("ssi", $_POST['time_in'], $_POST['time_out'], $_POST['id']);
    $message = $stmt->execute() ? "✅ Record updated!" : "❌ Error: " . $conn->error;
    $stmt->close();
}
$records = $conn->query("SELECT id,date,time_in,time_out FROM attendance ORDER BY date DESC,id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Attendance</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <nav class="nav">
        <div class="logo"><a href="https://www.facebook.com/MeRISEEnglishAcademyCebu"><img src="images/MeRISE-png.png"></a></div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a><a href="welcome.php">Welcome</a>
            <div class="dropdown"><a href="#" class="dropbtn">Attendance</a>
                <div class="dropdown-content">
                    <a href="attendance-logs.php">View Logs</a><a href="update-time.php">Update</a><a href="delete-time.php">Delete</a>
                </div>
            </div>
            <div class="dropdown"><a href="#" class="dropbtn">Request</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit</a><a href="view-requests.php">My Requests</a>
                </div>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <h1>Update Attendance Records</h1>
    <?php if ($message): ?><div class="message"><?= $message ?></div><?php endif; ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Action</th>
        </tr>
        <?php while ($r = $records->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= $r['id'] ?><input type="hidden" name="id" value="<?= $r['id'] ?>"></td>
                    <td><?= $r['date'] ?></td>
                    <td><?= $r['time_in'] ?></td>
                    <td><?= $r['time_out'] ?></td>
                    <td><button type="submit">Update</button></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <footer class="footer">
        <div class="footer-container">
            <h3>MeRISE English Academy</h3>
            <p>ESY Building, Corner Juana Osmeña St., Brgy. Kamputhaw, Cebu City</p>
            <p><strong>Tel. (PLDT):</strong> (032) 345 8524 <br><strong>Tel. (Globe):</strong> (032) 479 0414</p>
            <p><strong>Email:</strong> <a href="mailto:academic_support@meriseinc.com">academic_support@meriseinc.com</a></p>
        </div>
        <div class="footer-bottom">
            <p>© 2025 MeRISE English Academy. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>