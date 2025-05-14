<?php
session_start();
require_once '../includes/config.php';

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

$employeeNo = $_SESSION['employee_no'];
$errorMessage = $successMessage = "";

// Retrieve admin data from the database
$sql = "SELECT * FROM admins WHERE employee_no = '$employeeNo'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $adminName = $row['admin_fullname'];
} else {
    $errorMessage = "Admin data not found.";
}

// Update admin information if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate current password
    $sql = "SELECT password FROM admins WHERE employee_no = '$employeeNo'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!password_verify($currentPassword, $row['password'])) {
        $errorMessage = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = "New password and confirm password do not match.";
    } else {
        // Update password if provided
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET password = '$hashedPassword' WHERE employee_no = '$employeeNo'";

        if (mysqli_query($conn, $sql)) {
            $successMessage = "Information updated successfully.";
        } else {
            $errorMessage = "Failed to update information.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/settings.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <!-- Header Section -->
    <header class="admin-header">
        <img src="assets/images/logo.png" height="80px">
        <h1>Settings</h1>
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

    <!-- Main Content Section -->
    <main class="admin-main">
        <section class="settings-section">
            <h2>System Settings</h2>

            <!-- Display success or error message -->
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php elseif ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>

            <form method="POST" class="settings-form">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" value="<?php echo htmlspecialchars($adminName); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="employer-number">Employer Number:</label>
                    <input type="text" id="employer-number" value="<?php echo htmlspecialchars($employeeNo); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="current-password">Current Password:</label>
                    <input type="password" id="current-password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
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