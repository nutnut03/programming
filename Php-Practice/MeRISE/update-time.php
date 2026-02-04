<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("DB Error");

// Safely get ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID, fetch all records for dropdown
if ($id <= 0) {
    $records = $conn->query("SELECT id, date, time_in, time_out FROM attendance ORDER BY date DESC, id DESC");
} else {
    // Fetch single record
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
    $date     = $_POST['date'];
    $time_in  = $_POST['time_in'];
    $time_out = $_POST['time_out'];

    $update = $conn->prepare("UPDATE attendance SET date = ?, time_in = ?, time_out = ? WHERE id = ?");
    $update->bind_param("sssi", $date, $time_in, $time_out, $id);

    if ($update->execute()) {
        header("Location: attendance-logs.php");
        exit();
    } else {
        echo "Error updating record: " . $update->error;
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

    <h1>Update Attendance Record</h1>

    <?php if ($id <= 0): ?>
        <form method="get">
            <label>Select a record to update:</label>
            <select name="id">
                <?php while ($r = $records->fetch_assoc()): ?>
                    <option value="<?= $r['id'] ?>">
                        <?= "ID " . $r['id'] . " | " . $r['date'] . " | In: " . ($r['time_in'] ?: '-') . " | Out: " . ($r['time_out'] ?: '-') ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Edit</button>
        </form>
    <?php else: ?>
        <form method="post">
            <label>Date:</label>
            <input type="date" name="date" value="<?= htmlspecialchars($attendance['date']) ?>"><br>
            <label>Time In:</label>
            <input type="datetime-local" name="time_in"
                value="<?= $attendance['time_in'] ? date('Y-m-d\TH:i', strtotime($attendance['time_in'])) : '' ?>"><br>
            <label>Time Out:</label>
            <input type="datetime-local" name="time_out"
                value="<?= $attendance['time_out'] ? date('Y-m-d\TH:i', strtotime($attendance['time_out'])) : '' ?>"><br>
            <button type="submit">Save Changes</button>
        </form>
    <?php endif; ?>
</body>

</html>