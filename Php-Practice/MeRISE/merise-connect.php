<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Database and Table</title>
    <link rel="stylesheet" href="merise-styles.css">
</head>

<body>
    <?php
    // Step 1: Connect to MySQL server (without selecting a database yet)
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully<br>";

    // Step 2: Create the database
    $sql = "CREATE DATABASE IF NOT EXISTS MeRISE_DB";
    if ($conn->query($sql) === TRUE) {
        echo "Database 'MeRISE_DB' created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }

    // Close first connection
    $conn->close();

    // Step 3: Connect again, this time selecting the new database
    $conn = new mysqli("localhost", "root", "", "MeRISE_DB");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected to MeRISE_DB successfully<br>";

    // Step 4: Create the table inside MeRISE_DB
    $sql = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    // Close final connection
    $conn->close();
    ?>
</body>

</html>