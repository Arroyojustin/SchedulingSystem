<?php
$host = 'localhost';  // Your database host
$dbname = 'room_scheduling';  // Your database name
$username = 'root';  // Your database username
$password = '';  // Your database password

// Create a new database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
