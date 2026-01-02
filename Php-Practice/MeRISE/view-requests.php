<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM requests WHERE username='$username' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Requests</title>
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
                <a href="#" class="dropbtn">Requests</a>
                <div class="dropdown-content">
                    <a href="request.php">Submit Request</a>
                    <a href="view-requests.php">View My Requests</a>
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
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['request_type'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['start_date'] ?: '-' ?></td>
                    <td><?= $row['end_date'] ?: '-' ?></td>
                    <td><?= $row['hours'] ?: '-' ?></td>
                    <td><?= $row['date_requested'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

    </div>

    <script>
        // ✅ Auto-clear success message after 5 seconds
        const msg = document.querySelector(".message");
        if (msg) {
            setTimeout(() => {
                msg.style.opacity = "0";
            }, 5000);
        }

        // ✅ Highlight selected request type
        const selectBox = document.querySelector("select[name='request_type']");
        if (selectBox) {
            selectBox.addEventListener("change", () => {
                selectBox.style.borderColor = "#3498db";
            });
        }

        // ✅ Add row hover effect for requests table
        const rows = document.querySelectorAll(".requests-container table tr");
        rows.forEach(row => {
            row.addEventListener("mouseenter", () => {
                row.style.backgroundColor = "#eaf2f8";
            });
            row.addEventListener("mouseleave", () => {
                row.style.backgroundColor = "";
            });
        });
    </script>

</body>


</html>