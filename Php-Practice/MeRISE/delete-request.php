<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("DB Error");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $username = $_SESSION['username'];

    // Only delete if request belongs to logged-in user
    $conn->query("DELETE FROM requests WHERE id=$id AND username='$username'");
}

header("Location: view-requests.php");
exit();
