<?php
include 'config.php';

$id = $_GET['id'];
$sql = "SELECT * FROM billing_records WHERE id=$id";
$result = $conn->query($sql);
$record = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $billing_address = $_POST['billing_address'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    $sql = "UPDATE billing_records SET billing_address='$billing_address', payment_method='$payment_method', amount='$amount', status='$status', date='$date' WHERE id=$id";

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
    <title>Edit Billing Record</title>
</head>
<body>
    <h2>Edit Billing Record</h2>
    <form method="POST" action="">
        Billing Address: <input type="text" name="billing_address" value="<?= $record['billing_address'] ?>" required><br>
        Payment Method: <input type="text" name="payment_method" value="<?= $record['payment_method'] ?>"><br>
        Amount: <input type="number" name="amount" step="0.01" value="<?= $record['amount'] ?>" required><br>
        Status: <input type="text" name="status" value="<?= $record['status'] ?>"><br>
        Date: <input type="date" name="date" value="<?= $record['date'] ?>" required><br>
        <input type="submit" value="Update Record">
    </form>
</body>
</html>
