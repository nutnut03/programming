<?php
// Connect to MySQL server (without specifying database first)
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$dbName = "MeRISE_DB";
if ($conn->query("CREATE DATABASE IF NOT EXISTS $dbName") === TRUE) {
    echo "Database '$dbName' is ready<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbName);

// Create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users(
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL, 
        password VARCHAR(255) NOT NULL,
        role ENUM('Admin','User') NOT NULL DEFAULT 'User'
    )",
    "requests" => "CREATE TABLE IF NOT EXISTS requests(
        id INT AUTO_INCREMENT PRIMARY KEY, 
        username VARCHAR(100) NOT NULL, 
        request_type ENUM('Leave','Overtime','Other') NOT NULL, 
        description TEXT, 
        start_date DATE, 
        end_date DATE, 
        hours DECIMAL(5,2), 
        date_requested DATE NOT NULL, 
        status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending'
    )",
    "attendance" => "CREATE TABLE IF NOT EXISTS attendance(
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        time_in DATETIME NOT NULL,
        time_out DATETIME DEFAULT NULL,
        date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table '$name' ready<br>";
    } else {
        echo "Error creating $name: " . $conn->error . "<br>";
    }
}

// Check if 'role' column exists before adding
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN role ENUM('Admin','User') NOT NULL DEFAULT 'User'");
    echo "Column 'role' added to users<br>";
}

$conn->close();
?>
