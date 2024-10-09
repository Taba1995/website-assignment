-- Switch to your existing database
USE three_friends_restaurant;

-- Table for storing menu items
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

-- Table for storing orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10, 2) NOT NULL
);

-- Table for order items (relates orders to menu items)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_item_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Insert some initial menu items
INSERT INTO menu_items (item_name, description, price, image_url) VALUES
('Sekam', 'Traditional Bhutanese dried pork dish.', 20.00, 'media/Sekam.jpeg'),
('Shakam', 'Dried beef with chili and cheese.', 20.00, 'media/Shakam.jpeg'),
('Goep', 'Spicy stir-fried tripe with Bhutanese spices.', 15.00, 'media/Goep.jpeg'),
('Ema Datsi', 'Bhutan\'s national dish made of chili and cheese.', 10.00, 'media/Ema Datsi.jpeg'),
('Kewa Datsi', 'A potato and cheese dish with a mild chili kick.', 10.00, 'media/Kewa Datsi.jpeg'),
('Dumplings', 'Traditional steamed dumplings with minced meat or vegetables.', 15.00, 'media/Dumplings.jpeg'),
('Shu Kam', 'Dried potatoes cooked with Bhutanese spices.', 10.00, 'media/Shu Kam.jpeg');
