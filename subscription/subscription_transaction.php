    <?php
    // Include database connection
    include '../db.php';

    // Start the session for storing messages
    session_start();

    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

// Add subscription
if (isset($_POST['add_subscription'])) {
    // Sanitize user input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

    // Check if the data is valid
    if (empty($name) || empty($amount) || empty($start_date) || empty($end_date)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Use prepared statements to insert data securely
    $stmt = $conn->prepare("INSERT INTO subscriptions (name, amount, start_date, end_date) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    $stmt->bind_param("ssss", $name, $amount, $start_date, $end_date);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Subscription added successfully!";
    } else {
        $_SESSION['error'] = "Error executing query: " . $stmt->error;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

    // Add transaction
    if (isset($_POST['add_transaction'])) {
        $subscription_id = mysqli_real_escape_string($conn, $_POST['subscription_id']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $date = mysqli_real_escape_string($conn, $_POST['transaction_date']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $subscription = $conn->query("SELECT amount FROM subscriptions WHERE id = $subscription_id");

        if ($subscription && $subscription->num_rows > 0) {
            $subscription_row = $subscription->fetch_assoc();
            $current_balance = $subscription_row['amount'];

            if ($status == 'success' && $current_balance >= $amount) {
                $stmt = $conn->prepare("INSERT INTO transactions (subscription_id, amount, transaction_date, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("idss", $subscription_id, $amount, $date, $status);

                if ($stmt->execute()) {
                    $new_balance = $current_balance - $amount;
                    $update_balance_sql = "UPDATE subscriptions SET amount = $new_balance WHERE id = $subscription_id";
                    if ($conn->query($update_balance_sql) === TRUE) {
                        $_SESSION['message'] = "Transaction added successfully! Balance updated.";
                    } else {
                        $_SESSION['error'] = "Error updating balance: " . $conn->error;
                    }
                } else {
                    $_SESSION['error'] = "Error: " . $conn->error;
                }
            } else {
                $_SESSION['error'] = "Insufficient balance or failed transaction.";
            }
        } else {
            $_SESSION['info'] = "No subscription available yet. Please add a subscription first.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Delete subscription
    if (isset($_POST['delete_subscription'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $stmt = $conn->prepare("DELETE FROM subscriptions WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Subscription deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting subscription: " . $conn->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Delete transaction
    if (isset($_POST['delete_transaction'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $stmt = $conn->prepare("DELETE FROM transactions WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Transaction deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting transaction: " . $conn->error;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Fetch subscriptions for dropdown
    $subscriptions = $conn->query("SELECT id, name FROM subscriptions");

    // Fetch subscriptions for listing
    $subscriptions_list = $conn->query("SELECT * FROM subscriptions");

    // Fetch transactions
    $transactions_list = $conn->query("SELECT transactions.*, subscriptions.name AS subscription_name 
        FROM transactions 
        JOIN subscriptions ON transactions.subscription_id = subscriptions.id
    ");
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Subscriptions and Transactions</title>
        <link rel="stylesheet" href="subs_.css">
    </head>
    <body>
        <div class="container">
            <h1>Subscription and Transaction Manager</h1>

            <!-- Display messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success-msg"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error-msg"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['info'])): ?>
                <div class="message"><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-button active" data-tab="subscriptions">Subscriptions</button>
                <button class="tab-button" data-tab="transactions">Transactions</button>
            </div>

            <!-- Tab Contents -->
            <div id="subscriptions" class="tab-content active">
            <h2>Add Subscription</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Subscription Name" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <input type="date" name="start_date" placeholder="Start Date" required>
    <input type="date" name="end_date" placeholder="End Date" required>
    <button type="submit" name="add_subscription">Add Subscription</button>
</form>

<h2>Subscriptions List</h2>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Amount</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $subscriptions_list->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_subscription" onclick="return confirmDelete()">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

            </div>

            <div id="transactions" class="tab-content">
                <h2>Add Transaction</h2>
                <form method="POST">
                    <select name="subscription_id" required>
                        <option value="">Select Subscription</option>
                        <?php while ($row = $subscriptions->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="amount" placeholder="Amount" required>
                    <input type="date" name="transaction_date" required>
                    <select name="status" required>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                    <button type="submit" name="add_transaction">Add Transaction</button>
                </form>

                <h2>Transactions List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Subscription</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $transactions_list->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['subscription_name']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['transaction_date']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_transaction" onclick="return confirmDelete()">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            // Confirm delete function
            function confirmDelete() {
                return confirm("Are you sure you want to delete this?");
            }

            // Tab switching functionality
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', () => {
                    const tab = button.getAttribute('data-tab');
                    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                    document.getElementById(tab).classList.add('active');
                });
            });
        </script>
    </body>
    </html> 
