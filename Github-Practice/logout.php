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
session_destroy();
header("Location: login.php");
exit();
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

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>

</body>

</html>