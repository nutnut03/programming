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

// ✅ Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "✅ Attendance record deleted successfully!";
    } else {
        $message = "❌ Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

// ✅ Fetch today's records
$today = date("Y-m-d");
$records = $conn->query("SELECT id, time_in, time_out FROM attendance WHERE date='$today' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Attendance</title>
    <link rel="stylesheet" href="merise-styles.css">
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
            <a href="update-time.php">Update Attendance</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <h1>Delete Attendance Records</h1>
    <?php if ($message !== ""): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $records->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['time_in'] ?></td>
                <td><?= $row['time_out'] ?: '-' ?></td>
                <td>
                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this record?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>


</html>