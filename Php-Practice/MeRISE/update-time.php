<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_in  = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? null;
    $date     = $_POST['date'] ?? null;

    // Basic validation
    if ($time_in && $date) {
        $update = $conn->prepare("UPDATE attendance SET time_in = ?, time_out = ?, date = ? WHERE id = ?");
        $update->bind_param("sssi", $time_in, $time_out, $date, $id);

        if ($update->execute()) {
            header("Location: attendance-logs.php");
            exit();
        } else {
            echo "Error updating record: " . $update->error;
        }
    } else {
        echo "Please fill in required fields.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Attendance</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <nav class="nav">
        <div class="logo">
            <a href="https://www.facebook.com/MeRISEEnglishAcademyCebu">
                <img src="images/MeRISE-png.png" alt="MeRISE Logo">
            </a>
        </div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a>
            <a href="welcome.php">Welcome</a>
            <div class="dropdown">
                <a href="#" class="dropbtn">Attendance</a>
                <div class="dropdown-content">
                    <a href="attendance-logs.php">View Logs</a>
                    <a href="update-time.php">Update</a>
                    <a href="delete-time.php">Delete</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#" class="dropbtn">Request</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit</a>
                    <a href="view-requests.php">My Requests</a>
                </div>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <h1>Update Attendance Record</h1>
    <form method="post">
        <label>Date:</label>
        <input type="date" name="date" value="<?= htmlspecialchars($record['date']) ?>"><br>
        <label>Time In:</label>
        <input type="time" name="time_in" value="<?= $record['time_in'] ? date('H:i', strtotime($record['time_in'])) : '' ?>"><br>
        <label>Time Out:</label>
        <input type="time" name="time_out" value="<?= $record['time_out'] ? date('H:i', strtotime($record['time_out'])) : '' ?>"><br>
        <button type="submit" class="btn btn-update">Save Changes</button>
    </form>
</body>

</html>