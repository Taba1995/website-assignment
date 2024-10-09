<?php
session_start(); // Start the session to manage login state

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Exit if connection fails
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a SQL query to fetch the admin details for the given username
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the given username was found
    if ($result->num_rows === 1) {
        // Fetch the admin details
        $admin = $result->fetch_assoc();

        // Verify the entered password against the hashed password in the database
        if (password_verify($password, $admin['password'])) {
            // If the password is correct, store the username in the session and redirect to the dashboard
            $_SESSION['admin'] = $admin['username'];
            header("Location: ../html/dashboard.html"); // Redirect to dashboard
            exit; // Ensure script stops after redirection
        } else {
            // If the password is incorrect, redirect back to the login page with an error message
            header("Location: admin.html?error=Incorrect password");
        }
    } else {
        // If no user is found, redirect back to the login page with an error message
        header("Location: admin.html?error=User not found");
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
