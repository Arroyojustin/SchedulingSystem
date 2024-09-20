<?php
session_start();
require 'config.php'; // Database connection

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}



// Check if a specific date is selected or use the current date by default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch schedules for the selected date
$stmt = $conn->prepare("
    SELECT schedules.*, rooms.room_name, users.name as user_name
    FROM schedules
    JOIN rooms ON schedules.room_id = rooms.room_id
    JOIN users ON schedules.user_id = users.user_id
    WHERE date = ?
    ORDER BY start_time
");
if (!$stmt) {
    die("Error in SQL statement: " . $conn->error);
}
$stmt->bind_param('s', $date);
$stmt->execute();
$schedules_result = $stmt->get_result();
?>

<?php
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule | Room Scheduling System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            margin: 50px auto;
            max-width: 800px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .schedule-form {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #343a40;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            color: #888;
            font-size: 16px;
        }

        
    </style>
</head>
<body>

<div class="container">
    <h2>Room Schedules for <?= date('F j, Y', strtotime($date)) ?></h2>

    <!-- Form to select a date -->
    <div class="schedule-form">
        <form method="GET" action="view_schedule.php">
            <input type="date" name="date" value="<?= $date ?>" required>
            <button type="submit">View Schedule</button>
        </form>
    </div>

    <!-- Schedule Table -->
    <table>
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Booked By</th>
                <th>Subject</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($schedules_result->num_rows > 0): ?>
                <?php while ($row = $schedules_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['room_name']) ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= date('h:i A', strtotime($row['start_time'])) ?></td>
                        <td><?= date('h:i A', strtotime($row['end_time'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="no-data">No bookings found for this date.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



</body>
</html>
