<?php
// Start session to handle user interactions if needed
session_start();

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Exit if the connection fails
}

// Query to fetch all feedback, ordered by the date submitted (latest first)
$sql = "SELECT * FROM feedback ORDER BY feedback_date DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback - 3 Friends Restaurant</title>

    <!-- Link to external CSS stylesheets for styling -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/view_feedback.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <!-- Restaurant logo for branding -->
            <img src="../media/logo.jpeg" alt="3 Friends Restaurant Logo" class="logo">
            <h1>Customer Feedback</h1>
        </div>

        <!-- Navigation menu for easy access to other pages -->
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="../html/About.html">About</a></li>
                <li><a href="../php/menu.php">Menu</a></li>
                <li><a href="../php/reservation.php">Reservations</a></li>
                <li><a href="../php/contact.php">Contact</a></li>
                <li><a href="../html/dashboard.html">Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content section for viewing feedback -->
    <div class="container">
        <h2>Feedback Overview</h2>
        <p>Here you can view all the customer feedback submitted to 3 Friends Restaurant. We value your insights and use them to improve our services!</p>
        
        <!-- Feedback table displaying the feedback information -->
        <table>
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Feedback</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there is feedback data to display -->
                <?php if ($result->num_rows > 0): ?>
                    <!-- Loop through each feedback entry and display it in the table -->
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['feedback_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Message displayed if no feedback is found -->
                    <tr>
                        <td colspan="5">No feedback found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer section with contact details -->
    <footer>
        <p>&copy; 2024 3 Friends Restaurant. All rights reserved.</p>
        <!-- Clickable contact phone numbers -->
        <p>Contact us: <a href="tel:0406232082">0406 232 082</a> / <a href="tel:0422080268">0422 080 268</a></p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
