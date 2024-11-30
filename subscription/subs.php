<?php
    include '../db.php';

    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (isset($_POST['add_subscription'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

        if (empty($name) || empty($amount) || empty($start_date) || empty($end_date)) {
            $_SESSION['error'] = "All fields are required!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

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

    $subscriptions = $conn->query("SELECT id, name FROM subscriptions");
    $subscriptions_list = $conn->query("SELECT * FROM subscriptions");
    $transactions_list = $conn->query("SELECT transactions.*, subscriptions.name AS subscription_name FROM transactions JOIN subscriptions ON transactions.subscription_id = subscriptions.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions and Transactions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    /* Global Reset */
/* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #111, #333); /* Gradient background */
    color: #fff;
    padding: 20px;
    transition: background-color 0.5s ease, transform 0.3s ease;
}

.container {
    background-color: #444;
    padding: 25px;
    border-radius: 20px; /* Larger rounded corners for a softer look */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Deeper shadow */
    width: 80%;
    margin: auto;
    color: #fff;
    font-family: 'Arial', sans-serif;
    transition: box-shadow 0.4s ease, transform 0.4s ease, background-color 0.4s ease;
    position: relative;
    overflow: hidden;
    animation: fadeIn 1s ease-in-out; /* Fade in animation for the container */
}

.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.05);
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.container:hover {
    box-shadow: 0 10px 30px rgba(255, 0, 0, 0.7);
    transform: translateY(-15px);
    background-color: #555;
}

.container:hover::before {
    opacity: 0; /* Fade out overlay on hover */
}

/* Header Styling */
h1, h2 {
    color: #ff4747;
    margin-bottom: 20px;
    margin-top: 20px;
    animation: slideInFromLeft 1s ease-out;
    font-weight: bold;
    text-transform: uppercase;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Subtle shadow for text */
}

h1:hover, h2:hover {
    color: #c0392b; /* Darker red on hover */
    transform: translateX(5px);
}

/* Tabs Styling */
.tabs {
    display: flex;
    margin-bottom: 20px;
    border-bottom: 3px solid #ddd;
    animation: fadeIn 1.2s ease-in-out;
}

.tab-button {
    flex: 1;
    padding: 12px;
    text-align: center;
    background-color: #ff4747;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px 5px 0 0;
    transition: background-color 0.3s, transform 0.3s ease, box-shadow 0.3s ease;
    font-weight: bold;
}

.tab-button:hover, .tab-button.active {
    background-color: #c0392b;
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Add shadow effect */
}

.tab-content {
    display: none;
    opacity: 0;
    animation: fadeIn 1s forwards;
}

.tab-content.active {
    display: block;
}

/* Message Styling */
.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    transition: transform 0.3s ease;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.message.success-msg {
    background-color: #2ecc71;
    color: white;
}

.message.error-msg {
    background-color: #ff4747;
    color: white;
}

.message.info-msg {
    background-color: #f39c12;
    color: white;
}

.message:hover {
    transform: translateY(5px); /* Slight lift on hover */
}

/* Table Styling */
table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    animation: fadeIn 1s forwards;
}

th, td {
    padding: 15px;
    text-align: left;
    border: 1px solid #ddd;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

th {
    background-color: #ff4747;
    color: white;
}

th:hover, td:hover {
    background-color: #e74c3c;
    transform: translateY(-3px);
}

/* Form Styling */
.form-group {
    margin-bottom: 20px;
    animation: slideInFromBottom 1s ease-out;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 8px;
    background-color: #333;
    color: white;
}

button {
    padding: 12px 25px;
    background-color: #ff4747;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s ease;
    font-size: 16px;
    font-weight: bold;
}

button:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Back to Dashboard Button Styling */
.back-to-dashboard {
    margin-top: 25px;
    text-align: center;
}

.back-to-dashboard button {
    padding: 12px 25px;
    font-size: 18px;
    background-color: #ff4747;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    transition: background-color 0.3s, transform 0.3s ease;
}

.back-to-dashboard button:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideInFromLeft {
    from {
        transform: translateX(-30px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideInFromBottom {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}


</style>


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
        <div class="message info-msg"><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-button active" data-tab="subscriptions">Subscriptions</button>
        <button class="tab-button" data-tab="transactions">Transactions</button>
        <button class="tab-button" onclick="window.location.href='../calendar/calendar.php'">Calendar</button>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content active" id="subscriptions">
        <h2>Manage Subscriptions</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="name">Subscription Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" required>
            </div>
            <button type="submit" name="add_subscription">Add Subscription</button>
        </form>

        <h2>Existing Subscriptions</h2>
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
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_subscription">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Transactions Tab -->
    <div class="tab-content" id="transactions">
        <h2>Manage Transactions</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group">
                <label for="subscription_id">Subscription</label>
                <select name="subscription_id" id="subscription_id">
                    <?php while ($row = $subscriptions->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Transaction Amount</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div class="form-group">
                <label for="transaction_date">Transaction Date</label>
                <input type="date" name="transaction_date" id="transaction_date" required>
            </div>
            <div class="form-group">
                <label for="status">Transaction Status</label>
                <select name="status" id="status" required>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <button type="submit" name="add_transaction">Add Transaction</button>
        </form>

        <h2>Existing Transactions</h2>
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
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_transaction">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="back-to-dashboard">
        <button onclick="window.location.href='../dashboard/dashboard.php'">Back to Dashboard</button>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById(button.getAttribute('data-tab')).classList.add('active');
        });
    });

</script>

</body>
</html>
