<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php
$conn = new mysqli("localhost", "root", "", "mydatabase");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
echo "Connected successfully<br>";

$sql = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
)";
echo $conn->query($sql) ? "Table users created successfully" : "Error: " . $conn->error;

$conn->close();
?>

<body>

</body>


</html>