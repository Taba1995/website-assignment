<?php
// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Exit if the connection fails
} else {
    echo "Database connection successful";
}

// Define the admin username and hash the password securely
$username = 'admin';
$password = password_hash('password', PASSWORD_DEFAULT); // Using password_hash to securely hash the password

// Prepare the SQL statement to insert the new admin user
$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";

// Prepare the statement to avoid SQL injection vulnerabilities
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password); // Bind the parameters

// Execute the statement and check if the insert was successful
if ($stmt->execute()) {
    echo "Admin user created successfully";
} else {
    echo "Error: " . $stmt->error; // Display error if something goes wrong
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
