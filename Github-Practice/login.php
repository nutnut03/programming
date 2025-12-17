<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="github-style.css">
</head>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mydatabase") or die("DB Fail");

if ($_POST) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && $_POST['password'] === $row['password']) {
        $_SESSION['username'] = $row['username'];
        header("Location: welcome.php");
        exit;
    } else echo "<script>alert('Invalid username or password!');</script>";
}
?>

<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="logo"><a href="https://www.facebook.com/MeRISEEnglishAcademyCebu"><img src="images/MeRISE-png.png"></a></div>
        <div class=" menu-toggle" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
            <a href="welcome.php">Welcome</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- ✅ Responsive Registration Form -->
    <div class="form-container">
        <form method="post" class="register-form">
            <h2>Login</h2>

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn">Login</button>

            <p class="redirect">Already have an account? <a href="login.php">Login here</a></p>
        </form>
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