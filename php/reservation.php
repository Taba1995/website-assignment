<?php
// Start session to manage user data if needed
session_start();

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to make a reservation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $reservation_date = $_POST['date'];
    $reservation_time = $_POST['time'];
    $people = $_POST['people'];
    $requests = $_POST['requests'];

    // Prepare the SQL query to insert the reservation
    $sql = "INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, people, special_requests)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $name, $email, $phone, $reservation_date, $reservation_time, $people, $requests);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $success_message = "Your reservation has been successfully made!";
    } else {
        $error_message = "Error: Could not make the reservation. Please try again.";
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
    <title>Reservations - 3 Friends Restaurant</title>
    
    <!-- Link to external stylesheets for styling -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/reservation.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <!-- Restaurant logo -->
            <img src="../media/logo.jpeg" alt="3 Friends Restaurant Logo" class="logo">
            <h1>Reservations</h1>
        </div>

        <!-- Navigation menu -->
        <nav>
            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="../html/About.html">About</a></li>
                <li><a href="../php/menu.php">Menu</a></li>
                <li><a href="../php/reservation.php">Reservations</a></li>
                <li><a href="../php/contact.php">Contact</a></li>
                <li><a href="../html/admin.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <!-- Reservation form section -->
    <section class="reservation-info">
        <h2>Make a Reservation</h2>
        <p>At 3 Friends Restaurant, we strive to provide a memorable dining experience. To reserve your table, please fill out the form below. We look forward to welcoming you!</p>
        
        <!-- Success or error messages -->
        <?php if (isset($success_message)) { echo "<p class='success-message'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>

        <!-- Reservation form -->
        <form action="reservation.php" method="post">
            <!-- Name field -->
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <!-- Email field -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <!-- Phone field -->
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <!-- Date field -->
            <label for="date">Reservation Date:</label>
            <input type="date" id="date" name="date" required>

            <!-- Time field -->
            <label for="time">Reservation Time:</label>
            <input type="time" id="time" name="time" required>

            <!-- Number of people field -->
            <label for="people">Number of People:</label>
            <input type="number" id="people" name="people" min="1" max="20" required>

            <!-- Special requests field -->
            <label for="requests">Special Requests:</label>
            <textarea id="requests" name="requests" placeholder="Any special requests?" rows="4"></textarea>

            <!-- Submit button -->
            <input type="submit" value="Book Reservation">
        </form>
    </section>

    <!-- Footer section -->
    <footer>
        <p>&copy; 2024 3 Friends Restaurant. All rights reserved.</p>
        <!-- Clickable phone numbers -->
        <p>Contact us: <a href="tel:0406232082">0406 232 082</a> / <a href="tel:0422080268">0422 080 268</a></p>
    </footer>
</body>
</html>
