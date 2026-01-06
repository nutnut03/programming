<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("DB Error");

$id = intval($_GET['id']);
$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM requests WHERE id=$id AND username='$username'");
$request = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['request_type'];
    $desc = $_POST['description'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $hours = $_POST['hours'];

    $conn->query("UPDATE requests SET 
        request_type='$type',
        description='$desc',
        start_date='$start',
        end_date='$end',
        hours='$hours'
        WHERE id=$id AND username='$username'");

    header("Location: view-requests.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Request</title>
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

    <h1>Update Request</h1>
    <form method="post">
        <label>Type:</label><input type="text" name="request_type" value="<?= $request['request_type'] ?>"><br>
        <label>Description:</label><input type="text" name="description" value="<?= $request['description'] ?>"><br>
        <label>Start Date:</label><input type="date" name="start_date" value="<?= $request['start_date'] ?>"><br>
        <label>End Date:</label><input type="date" name="end_date" value="<?= $request['end_date'] ?>"><br>
        <label>Hours:</label><input type="number" name="hours" value="<?= $request['hours'] ?>"><br>
        <button type="submit">Save Changes</button>
    </form>
</body>

</html>