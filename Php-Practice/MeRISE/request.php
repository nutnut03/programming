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
if ($_POST) {
    $stmt = $conn->prepare("INSERT INTO requests(username,request_type,description,start_date,end_date,hours,date_requested) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssdds", $_SESSION['username'], $_POST['request_type'], trim($_POST['description']), $_POST['start_date'] ?: null, $_POST['end_date'] ?: null, $_POST['hours'] !== "" ? (float)$_POST['hours'] : null, date("Y-m-d"));
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

    <div class="request-container">
        <h1>Submit Request</h1>
        <?php if ($message): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="POST">
            <label>Request Type</label>
            <select name="request_type" id="request_type" required>
                <option>Leave</option>
                <option>Overtime</option>
                <option>Other</option>
            </select>
            <div id="extra-fields">
                <label>Start Date</label><input type="date" name="start_date">
                <label>End Date</label><input type="date" name="end_date">
                <label>Hours</label><input type="number" name="hours" step="0.25" min="0">
            </div>
            <label>Description</label><textarea name="description" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        const type = document.getElementById("request_type"),
            extra = document.getElementById("extra-fields");

        function toggleFields() {
            extra.style.display = (type.value === "Leave" || type.value === "Overtime") ? "block" : "none";
        }
        type.addEventListener("change", toggleFields);
        toggleFields();

        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>

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