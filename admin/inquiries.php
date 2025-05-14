<?php
session_start();
include '../includes/config.php'; // Include your database connection

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

// Update status based on button click
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['inquiry_id']) && isset($_POST['action'])) { // Check if keys exist
        $inquiryId = $_POST['inquiry_id'];
        $action = $_POST['action'];

        // Update inquiry status based on action
        $status = '';
        if ($action === 'mark_reviewed') {
            $status = 'reviewed';
        } elseif ($action === 'mark_resolved') {
            $status = 'resolved';
        }

        if ($status) {
            $updateSql = "UPDATE contacts SET cont_status='$status' WHERE cont_id='$inquiryId'"; // Change 'id' to your primary key column
            mysqli_query($conn, $updateSql);
        }
    }
}


// Handle filtering of inquiries based on status
$statusFilter = isset($_POST['status']) ? $_POST['status'] : 'all';
$statusCondition = '';

if ($statusFilter === 'pending') {
    $statusCondition = "WHERE cont_status = 'pending'";
} elseif ($statusFilter === 'reviewed') {
    $statusCondition = "WHERE cont_status = 'reviewed'";
} elseif ($statusFilter === 'resolved') {
    $statusCondition = "WHERE cont_status = 'resolved'";
}

// Fetch inquiries from the database
$inquiries = []; // Array to hold the inquiries
$sql = "SELECT * FROM contacts $statusCondition ORDER BY cont_date DESC"; // Change to your actual column name for date
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $inquiries[] = $row; // Store each inquiry in the array
    }
} else {
    echo "Error fetching inquiries: " . mysqli_error($conn); // Error handling
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin dashboard for Zetech University Suggestion Box to manage and review student inquiries.">
    <title>Inquiries - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/inquiries.css">
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
        <h1>Inquiries</h1>
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
        <section class="inquiries-section">
            <h2>All Inquiries</h2>

            <!-- Filter/Search Options -->
            <form method="POST" class="filter-options">
                <div class="filter-group">
                    <label for="status-filter">Status:</label>
                    <select id="status-filter" name="status" onchange="this.form.submit()">
                        <option value="all" <?php echo ($statusFilter == 'all' ? 'selected' : ''); ?>>All</option>
                        <option value="pending" <?php echo ($statusFilter == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="reviewed" <?php echo ($statusFilter == 'reviewed' ? 'selected' : ''); ?>>Reviewed</option>
                        <option value="resolved" <?php echo ($statusFilter == 'resolved' ? 'selected' : ''); ?>>Resolved</option>
                    </select>
                </div>
            </form>

            <!-- Inquiries Table -->
            <div class="table-wrapper">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>#</th> <!-- Counter column -->
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inquiries)): ?>
                            <tr>
                                <td colspan="8">No inquiries found.</td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $counter = 1; // Initialize counter
                            foreach ($inquiries as $inquiry): ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td> <!-- Increment counter -->
                                    <td><?php echo htmlspecialchars($inquiry['cont_subject']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['cont_message']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['cont_names']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['cont_email']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['cont_date']); ?></td>
                                    <td><?php echo htmlspecialchars($inquiry['cont_status']); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['cont_id']; ?>">
                                            <button type="submit" name="action" value="mark_reviewed" class="btn-action btn-mark-reviewed">Mark Reviewed</button>
                                            <button type="submit" name="action" value="mark_resolved" class="btn-action btn-mark-resolved">Mark Resolved</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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

    // Store and restore scroll position
    window.addEventListener('beforeunload', function() {
        localStorage.setItem('scrollPosition', window.scrollY);
    });

    window.addEventListener('load', function() {
        if (localStorage.getItem('scrollPosition') !== null) {
            window.scrollTo(0, localStorage.getItem('scrollPosition'));
        }
    });
</script>

</html>