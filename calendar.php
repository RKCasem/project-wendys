<?php
$servername = "localhost"; // Default for XAMPP
$username = "root";        // Default for XAMPP
$password = "";            // Default password
$dbname = "crud_app";      // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get subscription data
$sql = "SELECT name, subscription_date, subscription_type, amount FROM subscriptions";
$result = $conn->query($sql);

$subscriptions = array();

// Check if any subscriptions exist
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Use the subscription date as both the start and end date by default
        $start_date = date('Y-m-d', strtotime($row['subscription_date']));
        $end_date = $start_date;  // By default, the end date is the same as the start date

        // Handle different subscription types
        if ($row['subscription_type'] == 'monthly') {
            $end_date = date('Y-m-d', strtotime("+1 month", strtotime($start_date)));
        }
        if ($row['subscription_type'] == 'recurring') {
            $end_date = date('Y-m-d', strtotime("+1 year", strtotime($start_date)));
        }

        // Add subscription to the list
        $subscriptions[] = array(
            'name' => $row['name'],
            'amount' => $row['amount'], // Include amount in the data
            'start_date' => $start_date,
            'end_date' => $end_date
        );
    }
}

// Close connection
$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Calendar</title>
    
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>

    <style>
        body {
            color: #333;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-top: 20px;
        }

        #calendar {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .fc-event {
            background-color: #2e9cca;
            color: white;
            font-weight: bold;
        }

        .fc-event:hover {
            background-color: #1f7b99;
        }

        .fc-day-grid-event {
            border-radius: 8px;
        }

        .fc-header-toolbar {
            background-color: #2e9cca;
            color: white;
        }

        .fc-button {
            background-color: #2e9cca;
            color: white;
            border-radius: 5px;
        }

        .fc-button:hover {
            background-color: #1f7b99;
        }

        .fc-day-grid-event .fc-content {
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>My Subscription Calendar</h1>

    <!-- Calendar Container -->
    <div id="calendar"></div>

    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: 'get-subscriptions.php',  // Fetch subscription data from the backend
                        dataType: 'json',
                        success: function(data) {
                            console.log('Received data:', data);

                            if (data && data.length > 0) {
                                var events = [];
                                $(data).each(function() {
                                    // Create event with name and amount in the title
                                    events.push({
                                        title: this.name + " - $" + this.amount,  // Display name and amount in the title
                                        start: this.start_date,  // Use the subscription date as start
                                        end: this.end_date,  // Use the calculated end date
                                        allDay: true,  // Mark as all-day events
                                        description: "Amount: $" + this.amount // Optional: Amount in the description
                                    });
                                });
                                callback(events);  // Pass events to FullCalendar
                            } else {
                                console.log('No events found');
                                callback([]);  // Empty calendar
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error loading subscription data:', error);
                            alert('Error loading subscription data.');
                        }
                    });
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,  // Allows editing of events (optional)
                droppable: true, // Allows dragging and dropping of events (optional)
                events: [],  // Default to empty array if no data is available
                eventRender: function(event, element) {
                    // Optionally display the amount in the event's description or tooltip
                    element.attr('title', event.description);  // Tooltip shows the amount
                }
            });
        });
    </script>

</body>
</html>
