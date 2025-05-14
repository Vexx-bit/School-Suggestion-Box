<?php
// Start the session
session_start();

// Include the database configuration file
include 'includes/config.php';

if (isset($_POST['submit'])) {
    // Collect and sanitize input data
    $studentNumber = mysqli_real_escape_string($conn, $_POST['student-number']);
    $password = $_POST['password'];

    // Query to check if the student number exists
    $sql = "SELECT * FROM students WHERE student_no='$studentNumber'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Store student number in session
            $_SESSION['student_no'] = $row['student_no'];

            // Redirect to a welcome page or dashboard
            echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Student number not found.'); window.history.back();</script>";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student login page for Zetech University Suggestion Box.">
    <title>Student Login - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

    <!-- Login Form Section -->
    <main class="login-section">
        <img src="assets/images/logo.png" height="60px">
        <h1>Student Login</h1>

        <form class="login-form" method="POST">
            <div class="form-group">
                <label for="student-number">Student Number:</label>
                <input type="text" id="student-number" name="student-number" placeholder="Enter student number" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" name="submit" class="btn-login">Login</button>
        </form>

        <p style="text-align: center;">Don't have an account? <a href="register.php">Register</a></p>
        <!-- Footer Section -->
        <footer class="login-footer">
            &copy; 2024 Zetech University Suggestion Box. All rights reserved.
        </footer>
    </main>

</body>

</html>