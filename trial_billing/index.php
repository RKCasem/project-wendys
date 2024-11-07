<?php
include 'config.php';

// Fetch all billing records
$sql = "SELECT * FROM billing_records";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Records</title>
</head>
<body>
    <h1>Billing Records</h1>
    <a href="create.php">Add New Record</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Billing Address</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['billing_address'] ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td><?= $row['amount'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['date'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
