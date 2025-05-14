<?php
session_start();
include '../includes/config.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['employee_no'])) {
    header("Location: login.php");
    exit();
}

$employeeNo = $_SESSION['employee_no'];

// Retrieve admin data
$sql = "SELECT * FROM admins WHERE employee_no = '$employeeNo'";
$result = mysqli_query($conn, $sql);

$adminName = "";
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $adminName = $row['admin_fullname'];
}

// Initialize filtering variables
$categoryFilter = isset($_POST['category']) ? $_POST['category'] : 'all';
$statusFilter = isset($_POST['status']) ? $_POST['status'] : 'all';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_submit'])) {
    $suggId = $_POST['sugg_id'];
    $feedback = mysqli_real_escape_string($conn, $_POST['sugg_feedback']);

    // Update the feedback in the database
    $updateSql = "UPDATE suggestions SET sugg_feedback='$feedback', sugg_status='Reviewed' WHERE sugg_id='$suggId'";
    if (mysqli_query($conn, $updateSql)) {
        echo "<script>alert('Feedback sent successfully!');</script>";
    } else {
        echo "<script>alert('Error sending feedback. Please try again.');</script>";
    }
}

// Construct query with filters
$query = "SELECT * FROM suggestions WHERE 1=1";
if ($categoryFilter !== 'all') {
    $query .= " AND sugg_category = '$categoryFilter'";
}
if ($statusFilter !== 'all') {
    $query .= " AND sugg_status = '$statusFilter'";
}
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin dashboard for Zetech University Suggestion Box to manage and review student suggestions.">
    <title>Suggestions - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/suggestions.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Basic styles for table border */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .content-table th,
        .content-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header class="admin-header">
        <img src="assets/images/logo.png" height="80px">
        <h1>Suggestions</h1>
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
        <section class="suggestions-section">
            <h2>All Suggestions</h2>

            <!-- Filter/Search Options -->
            <div class="filter-options">
                <form method="POST" action="suggestions.php">
                    <div class="filter-group">
                        <label for="category-filter">Category:</label>
                        <select id="category-filter" name="category" onchange="this.form.submit()">
                            <option value="all" <?php echo $categoryFilter === 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="academics" <?php echo $categoryFilter === 'academics' ? 'selected' : ''; ?>>Academics</option>
                            <option value="facilities" <?php echo $categoryFilter === 'facilities' ? 'selected' : ''; ?>>Facilities</option>
                            <option value="administration" <?php echo $categoryFilter === 'administration' ? 'selected' : ''; ?>>Administration</option>
                            <option value="other" <?php echo $categoryFilter === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status-filter">Status:</label>
                        <select id="status-filter" name="status" onchange="this.form.submit()">
                            <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="reviewed" <?php echo $statusFilter === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                            <option value="resolved" <?php echo $statusFilter === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Suggestions Table -->
            <div class="table-wrapper">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $suggID = $row['sugg_id'];
                                echo "<tr>
            <td>{$row['sugg_title']}</td>
            <td>{$row['sugg_category']}</td>
            <td>{$row['sugg_desc']}</td>
            <td>{$row['sugg_date']}</td>
            <td>{$row['sugg_status']}</td>
            <td>
                <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#replyModal{$suggID}'>Reply</button>
            </td>
        </tr>";
                        ?>

                                <!-- Reply Modal -->
                                <div class="modal fade" id="replyModal<?php echo $suggID; ?>" tabindex="-1" aria-labelledby="replyModalLabel<?php echo $suggID; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="replyModalLabel<?php echo $suggID; ?>">Reply to Suggestion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Suggestion details -->
                                                <p><strong>Suggestion Title:</strong> <?php echo htmlspecialchars($row['sugg_title']); ?></p>
                                                <p><strong>Suggestion Description:</strong> <?php echo htmlspecialchars($row['sugg_desc']); ?></p>

                                                <!-- Feedback Form -->
                                                <form method="POST" action="">
                                                    <input type="hidden" name="sugg_id" value="<?php echo htmlspecialchars($suggID); ?>">
                                                    <div class="mb-3">
                                                        <label for="suggFeedback<?php echo $suggID; ?>" class="form-label">Feedback</label>
                                                        <textarea class="form-control" id="suggFeedback<?php echo $suggID; ?>" name="sugg_feedback" rows="4" placeholder="Enter your reply..." required></textarea>
                                                    </div>
                                                    <button type="submit" name="reply_submit" class="btn btn-success">Send Reply</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>No suggestions found.</td></tr>";
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