<?php
session_start();

// Only allow admins (adjust your session role check as needed)
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Manila");
$conn = new mysqli("localhost", "root", "", "MeRISE_DB");

$message = "";

// Handle approval/rejection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $status = $_POST['status']; // "Approved" or "Rejected"

    $stmt = $conn->prepare("UPDATE requests SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    $message = "✅ Request #$id has been $status.";
}

// Fetch all pending requests
$result = $conn->query("SELECT * FROM requests WHERE status='Pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Approve Requests</title>
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
        <h1>Pending Requests</h1>

        <?php if ($message !== ""): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Description</th>
                <th>Date Requested</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['request_type'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['date_requested'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="status" value="Approved" class="btn-approve">Approve</button>
                            <button type="submit" name="status" value="Rejected" class="btn-reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
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

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
</body>

</html>