<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="github-style.css">
</head>

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

    <div class="bg"> </div>
    <!-- Home Page Content -->
    <div class="home-box">
        <!-- COMPANY CONTENT -->
        <section class="section">
            <h1>About Merise English Academy</h1>
            <p>MeRISE English Academy is a top-notch English Language School, formerly known as United Re-Growth Philippines Inc. Masters of Business English Academy (URG-MBA). It is a language academy specializing in General English, Business English, and Pronunciation teaching. It is the goal of the Academy to provide the best English language training for our students. Thus, the Academy requires instructors to undergo extensive teacher-training courses.

                MeRISE English Academy is proud to say that all our instructors are highly trained and have certifications related to English language teaching. MeRISE faculty take into heart what students need. Headed by the Academic Team, our instructors are trained to provide lessons customized and tailored to fit students’ levels, needs, and requests.

                Aside from competent teachers, the Academy also provides students with a holistic educational experience. The Academy is housed in an 8-story building conducive to learning. The building offers students a dormitory with state-of-the-art facilities and the services of a hotel. With students’ needs in mind, the ground floor of the building was intentionally leased to a restaurant that provides Japanese and Western fusion treats and dishes. A laundromat, convenience store, and salon-spa business can also be found on the ground floor of the building for students’ ease and convenience.</p>
        </section>
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