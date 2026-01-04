<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "MeRISE_DB") or die("DB Fail");
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

    <!-- Navigation -->
    <nav class="nav">
        <div class="logo"><a href="https://www.facebook.com/MeRISEEnglishAcademyCebu"><img src="images/MeRISE-png.png"></a></div>
        <div class="menu-toggle" onclick="toggleMenu()"><span></span><span></span><span></span></div>
        <div class="nav-links" id="navLinks">
            <a href="home.php">Home</a><a href="register.php">Register</a><a href="login.php">Login</a>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="form-container">
        <div class="container-box">
            <form method="post" class="register-form">
                <h2>Login</h2>
                <label>Username<input type="text" name="username" required></label>
                <label>Password<input type="password" name="password" required></label>
                <button type="submit" class="btn">Login</button>
                <p class="redirect">No account? <a href="register.php">Register here</a></p>
            </form>
        </div>
    </div>

    <!-- Footer -->
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

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
</body>

</html>