<?php
session_start();
require_once '../includes/config.php';

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

// Destroy the session to log the admin out
session_unset();
session_destroy();

// Display logout confirmation with redirection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Logout page for Zetech University Suggestion Box admin panel.">
    <title>Logout - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/logout.css">
</head>

<body>

    <!-- Header Section -->
    <header class="admin-header">
        <img src="assets/images/logo.png" height="80px">
        <h1>Log out</h1>
    </header>

    <!-- Logout Confirmation Section -->
    <main class="logout-section">
        <h1>You have been logged out</h1>
        <p>Thank you for managing the suggestion box. You will be redirected to the login page shortly.</p>
        <p>If you are not redirected, <a href="login.php">click here to go to the login page</a>.</p>
    </main>

</body>
<script>
    // Redirect to the login page after 3 seconds
    setTimeout(function() {
        window.location.href = "login.php";
    }, 3000);

    // Toggle navigation for responsive menu
    document.getElementById("nav-toggle").addEventListener("click", function() {
        document.getElementById("nav-menu").classList.toggle("show");
    });
</script>

</html>