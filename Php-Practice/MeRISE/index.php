<?php
$conn = new mysqli("localhost", "root", "", "MeRISE_DB");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// ✅ Create users table with role column
$sql_users = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin','User') NOT NULL DEFAULT 'User'
)";
echo $conn->query($sql_users) ? "Table 'users' created/updated successfully<br>" : "Error creating users table: " . $conn->error . "<br>";

// ✅ Create requests table
$sql_requests = "CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(100) NOT NULL, 
    request_type ENUM('Leave','Overtime','Other') NOT NULL, 
    description TEXT, 
    start_date DATE, 
    end_date DATE, 
    hours DECIMAL(5,2), 
    date_requested DATE NOT NULL, 
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending'
)";
echo $conn->query($sql_requests) ? "Table 'requests' created successfully<br>" : "Error creating requests table: " . $conn->error . "<br>";

// ✅ Create attendance table
$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    time_in DATETIME NOT NULL,
    time_out DATETIME DEFAULT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

echo $conn->query($sql_attendance) ? "Table 'attendance' created successfully<br>" : "Error: " . $conn->error . "<br>";

// ✅ If you already have a users table and just want to add role column:
$alter_users = "ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('Admin','User') NOT NULL DEFAULT 'User'";
$conn->query($alter_users);

$conn->close();
