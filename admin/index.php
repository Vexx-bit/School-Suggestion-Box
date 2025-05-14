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
    <meta name="description" content="Admin dashboard for Zetech University Suggestion Box to manage and review student suggestions.">
    <title>Admin Dashboard - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <!-- Header Section -->
    <header class="admin-header">
        <img src="assets/images/logo.png" height="80px">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <strong><?php echo strtok($adminName, ' '); ?></strong>! Manage and review student suggestions and inquiries effectively.</p>
    </header>

    <!-- Responsive Navigation Section -->
    <nav class="admin-nav">
        <div class="nav-toggle" id="nav-toggle">☰</div>
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
        <!-- Overview Section -->
        <section class="overview-section">
            <h2>Overview</h2>
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Suggestions</h3>
                    <p><?php

                        $sql = "SELECT * from suggestions";
                        $result = $conn->query($sql);
                        $count = 0;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                                $count = $count + 1;
                            }
                        }
                        echo "$count";
                        ?></p>
                </div>
                <div class="stat-card">
                    <h3>New Suggestions</h3>
                    <p><?php

                        $sql = "SELECT * from suggestions WHERE sugg_status='pending' ";
                        $result = $conn->query($sql);
                        $count = 0;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                                $count = $count + 1;
                            }
                        }
                        echo "$count";
                        ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Inquiries</h3>
                    <p><?php

                        $sql = "SELECT * from contacts ";
                        $result = $conn->query($sql);
                        $count = 0;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                                $count = $count + 1;
                            }
                        }
                        echo "$count";
                        ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending Reviews</h3>
                    <p>
                        <?php
                        // Modify queries to ensure they return the same number of columns
                        // For example, selecting specific fields from both tables and using placeholders (e.g., NULL as alias) where necessary
                        $sql = "
            SELECT sugg_id, 'suggestions' AS source, sugg_status AS status FROM suggestions WHERE sugg_status = 'pending'
            UNION ALL
            SELECT cont_id, 'contacts' AS source, cont_status AS status FROM contacts WHERE cont_status = 'pending'
        ";

                        $result = $conn->query($sql);

                        // Checking if the query ran successfully
                        if ($result) {
                            $count = $result->num_rows; // Counting the combined rows with num_rows
                            echo $count;
                        } else {
                            echo "Error: " . $conn->error; // Display SQL error if the query fails
                        }
                        ?>
                    </p>
                </div>


            </div>
        </section>

        <!-- Recent Suggestions Section -->
        <section class="recent-section">
            <h2>Recent Suggestions</h2>
            <div class="table-wrapper">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch the latest 2 suggestions
                        $sql_suggestions = "SELECT * FROM suggestions ORDER BY sugg_date DESC LIMIT 2";
                        $result_suggestions = mysqli_query($conn, $sql_suggestions);

                        if (mysqli_num_rows($result_suggestions) > 0) {
                            while ($row = mysqli_fetch_assoc($result_suggestions)) {
                                echo "<tr>
                                        <td>{$row['sugg_title']}</td>
                                        <td>{$row['sugg_category']}</td>
                                        <td>{$row['sugg_date']}</td>
                                        <td>{$row['sugg_status']}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No recent suggestions available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h2>Recent Inquiries</h2>
            <div class="table-wrapper">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch the latest 2 inquiries
                        $sql_inquiries = "SELECT * FROM contacts ORDER BY cont_date DESC LIMIT 2";
                        $result_inquiries = mysqli_query($conn, $sql_inquiries);

                        if (mysqli_num_rows($result_inquiries) > 0) {
                            while ($row = mysqli_fetch_assoc($result_inquiries)) {
                                echo "<tr>
                                        <td>{$row['cont_names']}</td>
                                        <td>{$row['cont_email']}</td>
                                        <td>{$row['cont_email']}</td>
                                        <td>{$row['cont_message']}</td>
                                        <td>{$row['cont_date']}</td>
                                        <td>{$row['cont_status']}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No recent inquiries available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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