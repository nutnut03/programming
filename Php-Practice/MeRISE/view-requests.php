<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("DB Error");
$result = $conn->query("SELECT * FROM requests WHERE username='{$_SESSION['username']}' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Requests</title>
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
            <div class="dropdown"><a href="#" class="dropbtn">Requests</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit</a><a href="view-requests.php">My Requests</a>
                </div>
            </div>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="requests-container">
        <h1>My Requests</h1>
        <table>
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Hours</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($r = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['request_type'] ?></td>
                    <td><?= $r['description'] ?></td>
                    <td><?= $r['start_date'] && $r['start_date'] !== "0000-00-00" ? date("M d, Y", strtotime($r['start_date'])) : "-" ?></td>
                    <td><?= $r['end_date'] && $r['end_date'] !== "0000-00-00" ? date("M d, Y", strtotime($r['end_date'])) : "-" ?></td>
                    <td><?= $r['hours'] ?: '-' ?></td>
                    <td><?= $r['date_requested'] ?></td>
                    <td><?= $r['status'] ?></td>
                    <td>
                        <a href="update-request.php?id=<?= $r['id'] ?>" class="btn btn-update">Update</a>
                        <a href="delete-request.php?id=<?= $r['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                    </td>

                </tr>
            <?php endwhile; ?>

    </div>

    <script>
        const rows = document.querySelectorAll(".requests-container table tr");
        rows.forEach(r => {
            r.onmouseenter = () => r.style.background = "#eaf2f8";
            r.onmouseleave = () => r.style.background = "";
        });

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