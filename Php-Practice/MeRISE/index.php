<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <?php
    $conn = new mysqli("localhost", "root", "", "MeRISE_DB");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully<br>";

    // ✅ Create users table
    $sql_users = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL
)";
    echo $conn->query($sql_users) ? "Table 'users' created successfully<br>" : "Error creating users table: " . $conn->error . "<br>";

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

    $sql = "CREATE TABLE IF NOT EXISTS attendance(
  id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    time_in DATETIME DEFAULT NULL,
    time_out DATETIME DEFAULT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

    $sql = "CREATE TABLE IF NOT EXISTS attendance(
  id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    time_in DATETIME DEFAULT NULL,
    time_out DATETIME DEFAULT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
    echo $conn->query($sql) ? "Table users created successfully" : "Error: " . $conn->error;

    // ✅ If you want to ALTER table later, run separately:
    $alter = "ALTER TABLE requests 
    ADD COLUMN IF NOT EXISTS start_date DATE NULL,
    ADD COLUMN IF NOT EXISTS end_date DATE NULL,
    ADD COLUMN IF NOT EXISTS hours DECIMAL(5,2) NULL";
    $conn->query($alter); // optional, only if you need to add columns afterwards

    $conn->close();
    ?>
</body>

</html>