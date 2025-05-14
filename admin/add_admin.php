<?php
session_start();
require_once '../includes/config.php';

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

$employeeNo = $_SESSION['employee_no'];

// Retrieve admin data from the database
$sql = "SELECT * FROM admins WHERE employee_no = '$employeeNo'";
$result = mysqli_query($conn, $sql);

$adminName = "";
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $adminName = $row['admin_fullname'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin settings page for Zetech University Suggestion Box to configure system preferences.">
    <title>Settings - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/add_admin.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <?php
    // Database connection
    include '../includes/config.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $conn->real_escape_string($_POST['names']);
        $employeeNo = $conn->real_escape_string($_POST['epmloyeeNo']);
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        // Check if passwords match
        if ($password !== $cpassword) {
            echo "<p style='color: red;'>Passwords do not match.</p>";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into database
            $sql = "INSERT INTO admins (admin_fullname, employee_no, password) VALUES ('$fullname', '$employeeNo', '$hashedPassword')";

            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>New admin added successfully.</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
    ?>

    <!-- Header Section -->
    <header class="admin-header">
        <img src="assets/images/logo.png" height="80px">
        <h1>Add Admin</h1>
    </header>

    <!-- Responsive Navigation Section -->
    <nav class="admin-nav">
        <div class="nav-toggle" id="nav-toggle">
            ☰
        </div>
        <ul id="nav-menu">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="suggestions.php">View Suggestions</a></li>
            <li><a href="inquiries.php">Inquiries</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="add_admin.php">Add Admin</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Add Admin Form Section -->
    <main class="admin-main">
        <section class="add-admin-section">
            <h1>Add New Admin</h1>

            <form class="add-admin-form" method="POST" action="">
                <div class="form-group">
                    <label for="names">Full Name:</label>
                    <input type="text" id="names" name="names" placeholder="Enter full name" required>
                </div>

                <div class="form-group">
                    <label for="epmloyeeNo">Employee Number:</label>
                    <input type="text" id="epmloyeeNo" name="epmloyeeNo" placeholder="Enter employee no" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <div class="form-group">
                    <label for="cpassword">Confirm Password:</label>
                    <input type="password" id="cpassword" name="cpassword" placeholder="Confirm your password" required>
                </div>

                <button type="submit" class="btn-add-admin">Add Admin</button>
            </form>
        </section>
    </main>

</body>
<script>
    // Toggle navigation for responsive menu
    document.getElementById("nav-toggle").addEventListener("click", function() {
        document.getElementById("nav-menu").classList.toggle("show");
    });
</script>

</html>