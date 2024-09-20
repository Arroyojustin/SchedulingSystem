<?php
session_start();
require 'config.php'; // Database connection

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if the user is a teacher
if ($_SESSION['role'] !== 'teacher') {
    header("Location: dashboard.php");
    exit();
}

// Handle the booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_room'])) {
    // Sanitize input
    $room_id = htmlspecialchars($_POST['room_id']);
    $date = htmlspecialchars($_POST['date']);
    $start_time = htmlspecialchars($_POST['start_time']);
    $end_time = htmlspecialchars($_POST['end_time']);
    $subject = htmlspecialchars($_POST['subject']);

    // Validate input
    if (empty($room_id) || empty($date) || empty($start_time) || empty($end_time) || empty($subject)) {
        $error = "All fields are required.";
    } else {
        // Check for room availability (this example assumes overlapping booking prevention)
        $stmt = $conn->prepare("
            SELECT * FROM schedules 
            WHERE room_id = ? AND date = ? 
            AND (start_time < ? AND end_time > ?)
        ");
        if (!$stmt) {
            die("Error in SQL statement: " . $conn->error);
        }
        $stmt->bind_param('isss', $room_id, $date, $end_time, $start_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "This room is already booked for the selected time.";
        } else {
            // Insert the booking into the database
            $stmt = $conn->prepare("
                INSERT INTO schedules (room_id, user_id, date, start_time, end_time, subject) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            if (!$stmt) {
                die("Error in SQL statement: " . $conn->error);
            }
            $stmt->bind_param('iissss', $room_id, $_SESSION['user_id'], $date, $start_time, $end_time, $subject);
            
            if ($stmt->execute()) {
                $success = "Room successfully booked!";
            } else {
                $error = "Failed to book room. Please try again.";
            }
        }
    }
}

// Fetch available rooms
$rooms_result = $conn->query("SELECT * FROM rooms");

?>

<?php
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room | Room Scheduling System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
</head>
<body>

    <div class="booking-container">
        <h2>Book a Room</h2>

        <!-- Display error or success message -->
        <?php if (isset($error)): ?>
            <script>
                Swal.fire('Error', '<?= $error ?>', 'error');
            </script>
        <?php elseif (isset($success)): ?>
            <script>
                Swal.fire('Success', '<?= $success ?>', 'success');
            </script>
        <?php endif; ?>

        <!-- Room Booking Form -->
        <form method="POST" action="book_room.php">
            <select name="room_id" required>
                <option value="">Select a Room</option>
                <?php while ($room = $rooms_result->fetch_assoc()): ?>
                    <option value="<?= $room['room_id'] ?>"><?= htmlspecialchars($room['room_name']) ?> (Capacity: <?= $room['capacity'] ?>)</option>
                <?php endwhile; ?>
            </select>

            <input type="date" name="date" placeholder="Select Date" required>
            <input type="time" name="start_time" placeholder="Start Time" required>
            <input type="time" name="end_time" placeholder="End Time" required>
            <input type="text" name="subject" placeholder="Enter Subject" required>
            
            <button type="submit" name="book_room">Book Room</button>
        </form>
    </div>

</body>
</html>
