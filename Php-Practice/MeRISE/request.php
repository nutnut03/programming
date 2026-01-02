<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Manila");
$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("DB Error");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $type     = $_POST['request_type'];
    $desc     = trim($_POST['description']);
    $date     = date("Y-m-d");

    $start = $_POST['start_date'] ?: null;
    $end   = $_POST['end_date'] ?: null;
    $hours = $_POST['hours'] !== "" ? (float)$_POST['hours'] : null;

    $stmt = $conn->prepare("
        INSERT INTO requests
        (username, request_type, description, start_date, end_date, hours, date_requested)
        VALUES (?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssdds",
        $username,
        $type,
        $desc,
        $start,
        $end,
        $hours,
        $date
    );

    $stmt->execute();
    $stmt->close();

    $message = "✅ Request submitted successfully!";
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Request</title>
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
    <!-- FORM CONTAINER -->
    <div class="request-container">
        <h1>Submit Request</h1>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Request Type</label>
            <select name="request_type" id="request_type" required>
                <option value="Leave">Leave</option>
                <option value="Overtime">Overtime</option>
                <option value="Other">Other</option>
            </select>

            <div id="extra-fields">
                <label>Start Date</label>
                <input type="date" name="start_date">

                <label>End Date</label>
                <input type="date" name="end_date">

                <label>Hours</label>
                <input type="number" name="hours" step="0.25" min="0">
            </div>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        const type = document.getElementById("request_type");
        const extra = document.getElementById("extra-fields");

        function toggleFields() {
            extra.style.display =
                (type.value === "Leave" || type.value === "Overtime") ?
                "block" : "none";
        }

        type.addEventListener("change", toggleFields);
        toggleFields(); // run on page load
    </script>
</body>

</html>