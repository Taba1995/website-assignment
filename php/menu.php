<?php
// Start the session to manage the shopping cart
session_start();

// Establish a connection to the database
$conn = new mysqli('127.0.0.1', 'root', '', 'three_friends_restaurant');

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch menu items from the database
$sql = "SELECT * FROM menu_items";
$result = $conn->query($sql);

// Handle "Add to Cart" functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = 1;

    // Create an associative array for the cart item
    $cart_item = [
        'item_id' => $item_id,
        'item_name' => $item_name,
        'price' => $price,
        'quantity' => $quantity
    ];

    // Initialize the cart session if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart and update the quantity
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['item_id'] == $item_id) {
            $item['quantity']++;  // Increase the quantity if the item is already in the cart
            $item_exists = true;
            break;
        }
    }

    // If the item doesn't exist in the cart, add it
    if (!$item_exists) {
        $_SESSION['cart'][] = $cart_item;
    }
}

// Handle "Clear Cart" functionality
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']); // Remove all items from the cart
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $total_price = $_POST['total_price'];

    // Insert the order into the "orders" table
    $order_sql = "INSERT INTO orders (customer_name, customer_email, customer_phone, total_price) 
                  VALUES ('$customer_name', '$customer_email', '$customer_phone', '$total_price')";

    if ($conn->query($order_sql)) {
        $order_id = $conn->insert_id; // Get the ID of the newly created order

        // Insert each cart item into the "order_items" table
        foreach ($_SESSION['cart'] as $cart_item) {
            $menu_item_id = $cart_item['item_id'];
            $quantity = $cart_item['quantity'];
            $order_item_sql = "INSERT INTO order_items (order_id, menu_item_id, quantity) 
                               VALUES ('$order_id', '$menu_item_id', '$quantity')";
            $conn->query($order_item_sql);
        }

        // Clear the cart after the order is placed
        unset($_SESSION['cart']);
        $success_message = "Order placed successfully!";
    } else {
        $error_message = "Failed to place the order: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - 3 Friends Restaurant</title>
    
    <!-- Linking external stylesheets -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <!-- Restaurant logo -->
            <img src="../media/logo.jpeg" alt="3 Friends Restaurant Logo" class="logo">
            <h1>Menu</h1>
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

    <!-- Menu section showing available dishes -->
    <section class="menu">
        <img src="../media/Background.jpg" alt="Restaurant interior" class="banner-image">
        
        <table>
            <thead>
                <tr>
                    <th>Dish Image</th>
                    <th>Dish Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through the fetched menu items and display them -->
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="../<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>" class="dish-image"></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="item_name" value="<?php echo $row['item_name']; ?>">
                            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                            <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- Cart section showing items in the cart -->
    <section class="cart">
        <h2>Your Cart</h2>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                foreach ($_SESSION['cart'] as $item): 
                    $total_price += $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo $item['item_name']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Display total price -->
        <h3>Total: $<?php echo number_format($total_price, 2); ?></h3>

        <!-- Button to clear the cart -->
        <form method="post">
            <button type="submit" name="clear_cart" class="clear-cart-button">Clear Cart</button>
        </form>

        <!-- Order form for placing an order -->
        <section class="order-form">
            <h2>Place Your Order</h2>
            <form method="post">
                <input type="text" name="customer_name" placeholder="Your Name" required>
                <input type="email" name="customer_email" placeholder="Your Email" required>
                <input type="tel" name="customer_phone" placeholder="Your Phone Number" required>
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                <button type="submit" name="place_order">Place Order</button>
            </form>
        </section>

        <!-- Display success or error message -->
        <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
        
        <?php else: ?>
        <p>Your cart is empty.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2024 3 Friends Restaurant. All rights reserved.</p>
        <p>Contact us: <a href="tel:0406232082">0406 232 082</a> / <a href="tel:0422080268">0422 080 268</a></p>
    </footer>
</body>
</html>
