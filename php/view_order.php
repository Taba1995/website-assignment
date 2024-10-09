<?php
// Start the session if needed to manage user interactions
session_start();

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Stop the script if the connection fails
}

// SQL query to fetch all orders and the corresponding ordered items
$sql = "
    SELECT 
        orders.id AS order_id, 
        orders.customer_name, 
        orders.customer_email, 
        orders.customer_phone, 
        orders.order_date, 
        orders.total_price,
        -- Concatenate item names and quantities for each order
        GROUP_CONCAT(CONCAT(menu_items.item_name, ' (x', order_item_summary.total_quantity, ')') SEPARATOR ', ') AS items
    FROM orders
    -- Subquery to summarize order items per order
    LEFT JOIN (
        SELECT 
            order_items.order_id, 
            order_items.menu_item_id, 
            SUM(order_items.quantity) AS total_quantity
        FROM order_items
        GROUP BY order_items.order_id, order_items.menu_item_id
    ) AS order_item_summary ON orders.id = order_item_summary.order_id
    -- Join with menu items to get item names
    LEFT JOIN menu_items ON order_item_summary.menu_item_id = menu_items.id
    -- Group by order to ensure each order has a single row with all its items concatenated
    GROUP BY orders.id
    ORDER BY orders.order_date DESC
";

$result = $conn->query($sql); // Execute the query and fetch the results
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - 3 Friends Restaurant</title>

    <!-- Linking external CSS files for styling -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/view_order.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <!-- Restaurant logo for branding -->
            <img src="../media/logo.jpeg" alt="3 Friends Restaurant Logo" class="logo">
            <h1>View Orders</h1>
        </div>

        <!-- Navigation bar for quick access to other pages -->
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

    <!-- Main content section to display order details -->
    <div class="container">
        <h2>Order Details</h2>
        <p>Below are the details of all the orders placed at 3 Friends Restaurant. You can review customer information and the items they ordered.</p>

        <!-- Orders table -->
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Order Date</th>
                    <th>Items Ordered</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there are any orders -->
                <?php if ($result->num_rows > 0): ?>
                    <!-- Loop through each order and display details in the table -->
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['customer_email']; ?></td>
                            <td><?php echo $row['customer_phone']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['items']; ?></td>
                            <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Display a message if no orders are found -->
                    <tr>
                        <td colspan="7">No orders found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer with contact information -->
    <footer>
        <p>&copy; 2024 3 Friends Restaurant. All rights reserved.</p>
        <!-- Clickable phone numbers for easy contact -->
        <p>Contact us: <a href="tel:0406232082">0406 232 082</a> / <a href="tel:0422080268">0422 080 268</a></p>
    </footer>
</body>
</html>

<?php
// Close the database connection after rendering the page
$conn->close();
?>
