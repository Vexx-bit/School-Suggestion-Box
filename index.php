<?php
session_start();
include 'includes/config.php';

// Initialize user variables
$studentNo = null;
$studentName = null;
$studentId = null;

// Check if user is logged in
if (isset($_SESSION['student_no'])) {
    $studentNo = $_SESSION['student_no'];

    // Retrieve user data from the database
    $sql = "SELECT * FROM students WHERE student_no = '$studentNo'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $studentName = $row['student_names'];
        $studentId = $row['student_id'];
    }
} else {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}

// Handle suggestion submission
if (isset($_POST['submit'])) {
    // Collect and sanitize input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = "Pending";

    // Insert suggestion into the database
    $insertSql = "INSERT INTO suggestions (sugg_title, sugg_category, sugg_desc, sugg_status, student_id) 
                  VALUES ('$title', '$category', '$description', '$status', '$studentId')";

    if (mysqli_query($conn, $insertSql)) {
        // Suggestion submitted successfully
        echo "<script>alert('Your suggestion has been submitted successfully!'); window.location.href='index.php';</script>";
    } else {
        // Error occurred during submission
        echo "<script>alert('Error submitting suggestion: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}

// Count the number of reviewed suggestions for the student
$countReviewedSql = "SELECT COUNT(*) AS reviewed_count FROM suggestions WHERE sugg_status = 'Reviewed' AND student_id = '$studentId'";
$countResult = mysqli_query($conn, $countReviewedSql);
$countRow = mysqli_fetch_assoc($countResult);
$reviewedCount = $countRow['reviewed_count'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A platform for Zetech University students to submit anonymous suggestions to enhance the university experience.">
    <title>Zetech University Suggestion Box</title>
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
    </header>l>
    </nav>
    </header>

    <!-- Main Content Section -->
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h2>Shape the Future of Zetech University</h2>
                <p><strong>Hello <?php echo strtok($studentName, ' '); ?>!</strong>,share your insights and help foster positive changes at the university.</p>
                <a href="#suggestion-form" class="btn btn-primary">Submit a Suggestion</a>
            </div>
        </section>

        <!-- Suggestion Form Section -->
        <section id="suggestion-form" class="suggestion-form-section">
            <h3 style="color: var(--color-primary);">Submit Your Suggestion</h3>
            <form class="suggestion-form" method="POST">
                <label for="suggestion-title">Suggestion Title</label>
                <input type="text" id="suggestion-title" name="title" placeholder="Enter a brief title" required>

                <label for="suggestion-category">Category</label>
                <select id="suggestion-category" name="category" required>
                    <option value="" disabled selected>Select a Category</option>
                    <option value="academics">Academics</option>
                    <option value="facilities">Facilities</option>
                    <option value="administration">Administration</option>
                    <option value="other">Other</option>
                </select>

                <label for="suggestion-description">Description</label>
                <textarea id="suggestion-description" name="description" rows="5" placeholder="Provide a detailed description..." required></textarea>

                <button type="submit" name="submit" class="btn btn-submit">Submit Suggestion</button>
            </form>
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