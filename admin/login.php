<?php
session_start();
include '../includes/config.php';
// Initialize variables for error messages
$errorMessage = "";

// Check if the form is submitted
if (isset($_POST['login'])) {
    // Retrieve form data
    $employeNO = $_POST['employee_no'];
    $password = $_POST['password'];

    // Retrieve admin data from the database
    $sql = "SELECT * FROM admins WHERE employee_no = '$employeNO'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['employee_no'] = $employeNO;
            header("Location: index.php");
            exit();
        } else {
            $errorMessage = "Incorrect password.";
        }
    } else {
        $errorMessage = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login page for Zetech University Suggestion Box admin panel.">
    <title>Login - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <!-- Login Form Section -->
    <main class="login-section">
        <h1>Admin Login</h1>

        <!-- Displaying error message -->
        <?php if ($errorMessage !== ""): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?><br>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="employee_no">Employee No:</label>
                <input type="text" id="email" name="employee_no" required placeholder="Enter your employee no">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" name="login" class="btn-login">Login</button>
        </form>
    </main>

</body>

</html>