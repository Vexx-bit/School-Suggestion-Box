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

// Initialize a variable for success/error messages
$message = '';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $messageContent = mysqli_real_escape_string($conn, $_POST['message']);

    // Set default status
    $status = 'pending'; // or any other default status you prefer

    // Insert data into contacts table
    $sql = "INSERT INTO contacts (cont_names, cont_email, cont_subject, cont_message, cont_status) 
            VALUES ('$name', '$email', '$subject', '$messageContent', '$status')";

    if (mysqli_query($conn, $sql)) {
        // Set success message
        $message = 'Message sent successfully!';
    } else {
        // Set error message
        $message = 'Failed to send message. Please try again.';
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
    <meta name="description" content="Contact us at Zetech University Suggestion Box.">
    <title>Contact Us - Zetech University Suggestion Box</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #213373;
            box-shadow: 0 0 5px rgba(33, 51, 115, 0.25);
        }
    </style>
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

    <!-- Contact Section -->
    <main class="contact-section">
        <section class="contact-info">
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>
            <h2>Get in Touch</h2>
            <p><strong>Hello <?php echo strtok($studentName, ' '); ?>!</strong></p><br>
            <p>We value your feedback and are here to assist with any inquiries you may have regarding the Suggestion Box.</p>
            <div class="contact-details">
                <p><strong>Email:</strong> suggestionbox@zetechuniversity.ac.ke</p>
                <p><strong>Phone:</strong> +254 769 582 811</p>
                <p><strong>Address:</strong> Zetech University Main Campus, Nairobi, Kenya</p>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="contact-form">
            <h2>Send Us a Message</h2>

            <form action="" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" name="submit">Send Message</button>
            </form>
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

</body>

</html>