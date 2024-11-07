-- Database: `inventorymanagement`

-- --------------------------------------------------------

-- Drop existing tables if they exist to avoid errors
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;

-- --------------------------------------------------------

-- Create categories table first to satisfy foreign key dependencies in products
CREATE TABLE `categories` (
  `category_id` INT(10) NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,  -- Unique constraint for category names
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data into categories
INSERT INTO `categories` (`category_id`, `category_name`) VALUES
  (1, 'Smartphones'),
  (2, 'Laptops'),
  (3, 'Watches'),
  (4, 'Audio'),
  (5, 'Televisions');

-- --------------------------------------------------------

-- Create products table with a foreign key to categories
CREATE TABLE `products` (
  `product_id` INT(20) NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(30) NOT NULL,
  `price` FLOAT NOT NULL,
  `stock_quantity` INT(10) NOT NULL,
  `category_id` INT(10),
  PRIMARY KEY (`product_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Insert sample data into products
INSERT INTO `products` (`product_id`, `product_name`, `price`, `stock_quantity`, `category_id`) VALUES
  (1, 'iPhone 14', 100000, 990, 1),
  (2, 'iPhone 13', 64900, 550, 1),
  (3, 'iPhone SE', 49000, 100, 1),
  (4, 'iPhone 12', 59900, 15000, 1),
  (5, 'MacBook Air 13', 99900, 596, 2),
  (6, 'MacBook Pro 14', 199900, 450, 2),
  (7, 'iMac24', 129900, 30, 2),
  (8, 'Mac Mini', 49000, 700, 2),
  (9, 'Apple Watch Ultra', 89900, 300, 3),
  (10, 'Apple Watch SE', 29900, 500, 3),
  (11, 'AirPods', 14900, 1500, 4),
  (12, 'AirPods Max', 59000, 900, 4),
  (13, 'Apple TV 8K', 599000, 150, 5);

-- --------------------------------------------------------

-- Create orders table with a foreign key to products
CREATE TABLE `orders` (
  `order_id` INT(10) NOT NULL AUTO_INCREMENT,
  `product_id` INT(20) NOT NULL,
  `order_quantity` INT(10) NOT NULL,
  `order_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data into orders
INSERT INTO `orders` (`order_id`, `product_id`, `order_quantity`, `order_date`) VALUES
  (1, 1, 2, '2023-06-20 10:00:00'),
  (2, 5, 1, '2023-06-21 12:00:00'),
  (3, 10, 3, '2023-06-22 15:00:00');

-- --------------------------------------------------------

-- Set AUTO_INCREMENT values
ALTER TABLE `products` AUTO_INCREMENT = 14;
ALTER TABLE `categories` AUTO_INCREMENT = 6;
ALTER TABLE `orders` AUTO_INCREMENT = 4;

-- --------------------------------------------------------

COMMIT;
