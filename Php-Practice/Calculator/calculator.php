<?php
// calculator.php

$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num1 = (float)$_POST['num1'];
    $num2 = (float)$_POST['num2'];
    $operation = $_POST['operation'];

    switch ($operation) {
        case "add":
            $result = $num1 + $num2;
            break;
        case "subtract":
            $result = $num1 - $num2;
            break;
        case "multiply":
            $result = $num1 * $num2;
            break;
        case "divide":
            $result = ($num2 != 0) ? $num1 / $num2 : "Error: Division by zero";
            break;
        default:
            $result = "Invalid operation";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="calculator-styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>

</head>

<body>
    <div class="calculator">
        <h2>Simple Calculator</h2>
        <form method="POST" action="">
            <input type="number" step="any" name="num1" placeholder="Enter first number" required>
            <input type="number" step="any" name="num2" placeholder="Enter second number" required>

            <select name="operation" required>
                <option value="add">Addition (+)</option>
                <option value="subtract">Subtraction (-)</option>
                <option value="multiply">Multiplication (×)</option>
                <option value="divide">Division (÷)</option>
            </select>

            <button type="submit">Calculate</button>
        </form>

        <?php if ($result !== ""): ?>
            <div class="result">Result: <?= $result ?></div>
        <?php endif; ?>
    </div>
</body>

</html>