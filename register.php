<?php
// Include the database configuration file
include 'includes/config.php';

if (isset($_POST['submit'])) {
    // Collect and sanitize input data
    $fullName = mysqli_real_escape_string($conn, $_POST['full-name']);
    $studentNumber = mysqli_real_escape_string($conn, $_POST['student-number']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Basic validation
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Check if the student number already exists
    $checkSql = "SELECT * FROM students WHERE student_no='$studentNumber'";
    $result = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Student number is already registered.'); window.history.back();</script>";
        exit();
    }

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert student data
    $sql = "INSERT INTO students (student_names, student_no, password) VALUES ('$fullName', '$studentNumber', '$hashedPassword')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Redirect to login.php with a success alert
        echo "<script>alert('Registration successful! Redirecting to login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
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
    <title>Student Register - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

    <!-- Register Form Section -->
    <main class="login-section">
        <img src="assets/images/logo.png" height="60px">
        <h1>Student Registration</h1>

        <form class="login-form" method="POST">
            <div class="form-group">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label for="student-number">Student Number:</label>
                <input type="text" id="student-number" name="student-number" placeholder="Enter student number" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required>
            </div>

            <button type="submit" name="submit" class="btn-login">Register</button>
        </form>

        <p style="text-align: center;">Already have an account? <a href="login.php">Log in</a></p>

        <!-- Footer Section -->
        <footer class="login-footer">
            &copy; 2024 Zetech University Suggestion Box. All rights reserved.
        </footer>
    </main>

</body>

</html>