<?php
session_start();
include 'includes/config.php';

$studentId = null;

// Check if user is logged in
if (isset($_SESSION['student_no'])) {
    $studentNo = $_SESSION['student_no'];

    // Retrieve user data from the database
    $fetch_sql = "SELECT * FROM students WHERE student_no = '$studentNo'";
    $fetched_result = mysqli_query($conn, $fetch_sql);

    if (mysqli_num_rows($fetched_result) === 1) {
        $row = mysqli_fetch_assoc($fetched_result);
        $studentName = $row['student_names'];
        $studentId = $row['student_id'];
    }
} else {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}

// Fetch feedbacks with status "Replied" for the logged-in student
$sql = "SELECT * FROM suggestions WHERE sugg_status = 'Reviewed' AND student_id = '$studentId'";
$result = mysqli_query($conn, $sql);

// Count the number of reviewed suggestions for the student
$countReviewedSql = "SELECT COUNT(*) AS reviewed_count FROM suggestions WHERE sugg_status = 'Reviewed' AND student_id = '$studentId'";
$countResult = mysqli_query($conn, $countReviewedSql);
$countRow = mysqli_fetch_assoc($countResult);
$reviewedCount = $countRow['reviewed_count'];

if (isset($_POST['mark_resolved']) && isset($_POST['sugg_id'])) {
    $suggId = $_POST['sugg_id'];

    // Update the suggestion status to 'Resolved'
    $updateSql = "UPDATE suggestions SET sugg_status = 'Resolved' WHERE sugg_id = '$suggId' AND student_id = '$studentId'";
    if (mysqli_query($conn, $updateSql)) {
        // Redirect back to the feedback page
        header('Location: feedbacks.php');
        exit;
    } else {
        echo "<script>alert('Error marking suggestion as resolved. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View your replied feedbacks on Zetech University's online suggestion box.">
    <title>Replied Feedbacks - Zetech University</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Header Section -->
    <header class="header">
        <img src="assets/images/logo_name.png" height="60px">
        <h1> Student Suggestion Box</h1>
        <p>Submit your feedback anonymously to help us create a better experience for everyone.</p>
        <nav class="nav">
            <ul>
                <li><a href="index.php">Submit Suggestion</a></li>
                <li><a href="feedbacks.php">Feedbacks <?php echo $reviewedCount > 0 ? "<span class='badge'>$reviewedCount</span>" : ""; ?></a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main>
        <section class="feedbacks-section">
            <h2>Your Feedbacks</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="feedbacks-container">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="feedback-card">
                            <h3 class="feedback-title"><?php echo htmlspecialchars($row['sugg_title']); ?></h3>
                            <p class="feedback-category"><strong>Category:</strong> <?php echo htmlspecialchars($row['sugg_category']); ?></p>
                            <p class="feedback-desc"><?php echo htmlspecialchars($row['sugg_desc']); ?></p>
                            <p class="feedback-reply"><strong>Reply:</strong> <?php echo htmlspecialchars($row['sugg_feedback']); ?></p>

                            <!-- Mark as Resolved Form -->
                            <?php if ($row['sugg_status'] == 'Reviewed'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="sugg_id" value="<?php echo $row['sugg_id']; ?>">
                                    <button type="submit" name="mark_resolved" id="mark_resolved">Mark as Resolved</button>

                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-feedback">You have no replied feedbacks yet.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Zetech University Suggestion Box. All rights reserved.</p>
        <div class="footer-links">
            <a href="#privacy-policy">Privacy Policy</a>
            <a href="#terms-of-use">Terms of Use</a>
        </div>
    </footer>

</body>

</html>