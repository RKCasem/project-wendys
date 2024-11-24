    <?php
    // Include database connection
    include 'db.php';

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
        $date = mysqli_real_escape_string($conn, $_POST['subscription_date']);
        $subscription_type = mysqli_real_escape_string($conn, $_POST['subscription_type']);

        // Check if the data is valid
        if (empty($name) || empty($amount) || empty($date) || empty($subscription_type)) {
            $_SESSION['error'] = "All fields are required!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Use prepared statements to insert data securely
        $stmt = $conn->prepare("INSERT INTO subscriptions (name, amount, subscription_date, subscription_type) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $_SESSION['error'] = "Error preparing statement: " . $conn->error;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $stmt->bind_param("ssss", $name, $amount, $date, $subscription_type);

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
        <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            color: #333;
            overflow-x: hidden;
        }
        form input, form select, form button {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease, box-shadow 0.3s ease;
            transform: scale(1);
            animation: fadeInInputs 0.5s ease-out;
        }

        /* Add hover animation */
        form input:hover, form select:hover, form button:hover {
            background-color: #f9f9f9;
            border-color: #0056b3;
            transform: scale(1.05); /* Slightly increase size */
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Add focus effect */
        form input:focus, form select:focus {
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            transform: scale(1.05); /* Consistent with hover */
        }

        /* Define fade-in animation for inputs */
        @keyframes fadeInInputs {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            font-size: 2.8em;
            margin-bottom: 10px;
            color: #444;
            letter-spacing: 2px;
        }

        h2 {
            text-align: center;
            font-size: 1.8em;
            margin: 30px 0 15px;
            color: #666;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .tab-button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            background-color: #f1f1f1;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 5px 5px 0 0;
            transition: background-color 0.3s;
        }

        .tab-button:hover {
            background-color: #ddd;
        }

        .tab-button.active {
            background-color: #007bff;
            color: #fff;
        }

        .message {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            border-radius: 6px;
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
        }

        .error-msg {
            background-color: #f8d7da;
            color: #721c24;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        form input, form select, form button {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        form input:focus, form select:focus {
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        form button {
            padding: 16px 20px; /* Increased padding */
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.4);
        }

        form button[name="delete_subscription"], 
        form button[name="delete_transaction"] {
            padding: 12px 25px; /* Increase horizontal padding (second value) */
            background: #dc3545; /* Add a distinct color for delete buttons, e.g., red */
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            
        }

        form button[name="delete_subscription"]:hover, 
        form button[name="delete_transaction"]:hover {
            background: #c82333; /* Slightly darker red on hover */
            transform: scale(1.05); /* Slightly increase size on hover */
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeInUp 1s ease-out;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table th:last-child, table td:last-child {
    width: 100px; /* Adjust the width as needed */
    text-align: center;
}

        table tr:hover {
            background: #f1f1f1;
            transition: background 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 600px) {
            form {
                grid-template-columns: 1fr;
            }
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
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
                    <input type="date" name="subscription_date" placeholder="Subscription Date" required>
                    <select name="subscription_type" required>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                    <button type="submit" name="add_subscription">Add Subscription</button>
                </form>

                <h2>Subscriptions List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $subscriptions_list->fetch_assoc()): ?>
                            <tr>

                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['subscription_type']; ?></td>
                                <td><?php echo $row['subscription_date']; ?></td>
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
