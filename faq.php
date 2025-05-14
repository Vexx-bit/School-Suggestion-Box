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
    <meta name="description" content="Frequently Asked Questions for the Zetech University Suggestion Box.">
    <title>FAQ - Zetech University Suggestion Box</title>
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

    <!-- FAQ Section -->
    <main class="faq-section">
        <section class="faq">
            <h2>Frequently Asked Questions</h2>
            <ul class="faq-list">
                <!-- FAQ Item 1 -->
                <li class="faq-item">
                    <h3 class="faq-question">What is the purpose of the Suggestion Box?</h3>
                    <p class="faq-answer">The Suggestion Box is designed to provide students with a confidential platform to submit feedback and suggestions that will help improve the university experience.</p>
                </li>

                <!-- FAQ Item 2 -->
                <li class="faq-item">
                    <h3 class="faq-question">Is my suggestion anonymous?</h3>
                    <p class="faq-answer">Yes, all suggestions are anonymous unless you choose to include your personal information.</p>
                </li>

                <!-- FAQ Item 3 -->
                <li class="faq-item">
                    <h3 class="faq-question">How can I be sure my suggestion will be considered?</h3>
                    <p class="faq-answer">All suggestions are reviewed by a dedicated team at the university. Relevant suggestions are forwarded to the respective departments for consideration and potential action.</p>
                </li>

                <!-- FAQ Item 4 -->
                <li class="faq-item">
                    <h3 class="faq-question">What types of suggestions can I submit?</h3>
                    <p class="faq-answer">You may submit suggestions related to academics, facilities, administration, or any other aspects of university life that may improve student experience.</p>
                </li>
            </ul>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Zetech University Suggestion Box. All rights reserved.</p>
        <div class="footer-links">
            <a href="privacy.html">Privacy Policy</a>
            <a href="terms.html">Terms of Use</a>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>

</html>