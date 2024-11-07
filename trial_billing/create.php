<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $billing_address = $_POST['billing_address'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    $sql = "INSERT INTO billing_records (billing_address, payment_method, amount, status, date) 
            VALUES ('$billing_address', '$payment_method', '$amount', '$status', '$date')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Billing Record</title>
</head>
<body>
    <h2>Add New Billing Record</h2>
    <form method="POST" action="">
        Billing Address: <input type="text" name="billing_address" required><br>
        Payment Method: <input type="text" name="payment_method"><br>
        Amount: <input type="number" name="amount" step="0.01" required><br>
        Status: <input type="text" name="status"><br>
        Date: <input type="date" name="date" required><br>
        <input type="submit" value="Add Record">
    </form>
</body>
</html>
