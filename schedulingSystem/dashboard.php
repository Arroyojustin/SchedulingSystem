<?php
session_start();

// Check if the user is logged in (session is set)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['name'])) {
    // Redirect to login page if session variables are not set
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .navbar-custom {
            background-color: #343a40;
        }
        .nav-link, .navbar-brand {
            color: white;
        }
        .nav-link:hover, .navbar-brand:hover {
            color: white;
        }
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }
    </style>
</head>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Room Scheduling System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="book_room.php">Book a Room</a></li>
                        <li><a class="dropdown-item" href="view_schedule.php">View Schedule</a></li>
                        <li><?php if ($_SESSION['role'] === 'admin'): ?><a a class="dropdown-item" href="create_room.php">Create New Room</a><?php endif; ?></li>
                        
    
                    </ul>
                </li>
            </ul>

            <!-- Profile Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <div class="dashboard-container">
                    <li class="welcome">
                    <a class="navbar-brand"><?= isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest' ?>!</a>
                    <!-- Check if the session variable is set before displaying it -->
                    </li>
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>



</body>
</html>
