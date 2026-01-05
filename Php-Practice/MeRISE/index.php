<?php
$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

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
    echo $conn->query($sql) ? "Table '$name' ready<br>" : "Error creating $name: " . $conn->error . "<br>";
}

// Add role column if missing
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('Admin','User') NOT NULL DEFAULT 'User'");

$conn->close();
