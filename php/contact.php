<?php
// Start the session if not already started
session_start();

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection to the database is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Stop if the connection fails
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];  // Capturing the email field
    $message = $_POST['message'];

    // Prepare an SQL query to insert feedback into the database using prepared statements for security
    $sql = "INSERT INTO feedback (name, email, feedback_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message); // Bind parameters for security

    // Execute the query and check for success
    if ($stmt->execute()) {
        $success_message = "Thank you for your feedback!";
    } else {
        $error_message = "Error submitting feedback: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - 3 Friends Restaurant</title>
    
    <!-- Linking external stylesheets for styling -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/contact.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <!-- Logo image -->
            <img src="../media/logo.jpeg" alt="3 Friends Restaurant Logo" class="logo">
            <h1>Contact Us</h1>
        </div>

        <!-- Navigation menu -->
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="reservation.php">Reservations</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="../html/admin.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contact form section -->
    <section class="contact-info">
        <h2>We'd Love to Hear From You!</h2>
        <p>Your feedback is important to us. Whether you have a question, a suggestion, or just want to say hello, please fill out the form below. We will respond as soon as possible.</p>

        <!-- Display success or error messages -->
        <?php if (isset($success_message)) { echo "<p class='success-message'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>

        <!-- Feedback form -->
        <form action="contact.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            
            <input type="submit" value="Submit Feedback">
        </form>
    </section>

    <!-- Footer section -->
    <footer>
        <p>&copy; 2024 3 Friends Restaurant. All rights reserved.</p>
        <!-- Clickable contact phone numbers -->
        <p>Contact us: <a href="tel:0406232082">0406 232 082</a> / <a href="tel:0422080268">0422 080 268</a></p>
    </footer>
</body>
</html>
