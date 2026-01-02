<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="github-style.css">
</head>
<?php
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
echo "Connected successfully<br>";

$sql = "CREATE DATABASE mydatabase";
echo $conn->query($sql) ? "Database created successfully" : "Error: " . $conn->error;

$conn->close();
?>

<body>

</body>

</html>