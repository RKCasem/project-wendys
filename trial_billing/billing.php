<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subscription</title>
</head>
<body>

<h2>Add Transaction</h2>
<form action="billing.php" method="POST">
    <input type="text" name="description" placeholder="Description" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <input type="text" name="status" placeholder="Status" required>
    <button type="submit" name="create">Add Transaction</button>
</form>

<h2>Transaction History</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php
    // Display transactions
    $sql = "SELECT * FROM user";  // Adjust column names as needed
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['description']}</td>
            <td>{$row['amount']}</td>
            <td>{$row['status']}</td>
            <td>
                <a href='billing.php?edit={$row['id']}'>Edit</a>
                <a href='billing.php?delete={$row['id']}'>Delete</a>
            </td>
        </tr>";
    }
    ?>

</table>

<?php
// Create transaction
if (isset($_POST['create'])) {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $sql = "INSERT INTO user (description, amount, status) VALUES ('$description', '$amount', '$status')";
    $conn->query($sql);
    header("Location: billing.php");
}

// Delete transaction
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM user WHERE id=$id";
    $conn->query($sql);
    header("Location: billing.php");
}

// Edit transaction (you'll need an additional form and logic to handle updates)
?>
</body>
</html>
