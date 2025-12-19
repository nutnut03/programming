<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="calculator-styles.css">
</head>
<?php
$conn = new mysqli("localhost", "root", "", "calculator_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
echo "Connected successfully<br>";

$sql = "CREATE TABLE IF NOT EXISTS calculations(
   id INT AUTO_INCREMENT PRIMARY KEY,
    num1 FLOAT NOT NULL,
    num2 FLOAT NOT NULL,
    operation VARCHAR(20) NOT NULL,
    result FLOAT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
echo $conn->query($sql) ? "Table users created successfully" : "Error: " . $conn->error;

$conn->close();
?>

<body>

</body>

</html>