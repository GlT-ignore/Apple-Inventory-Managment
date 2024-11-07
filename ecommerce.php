<?php
header('Content-Type: application/json');
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "inventorymanagement");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Handle POST request for placing orders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_quantity'], $_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['order_quantity']);

    // Validate stock availability
    $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['error' => 'Not enough stock available']);
        exit;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update stock
        $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (product_id, order_quantity, order_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $product_id, $quantity);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['success' => 'Order placed successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => 'Error processing order: ' . $e->getMessage()]);
    }
    exit;
}

// Handle GET requests for product listing and details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'get_products':
                $result = $conn->query("
                    SELECT p.*, c.category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.category_id 
                    ORDER BY p.product_name
                ");
                
                $products = [];
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                echo json_encode(['products' => $products]);
                break;

            case 'get_product_details':
                if (!isset($_GET['product_id'])) {
                    echo json_encode(['error' => 'Product ID required']);
                    break;
                }
                
                $product_id = intval($_GET['product_id']);
                $stmt = $conn->prepare("
                    SELECT p.*, 
                           c.category_name,
                           COUNT(o.order_id) as total_orders,
                           COALESCE(SUM(o.order_quantity), 0) as total_quantity_ordered
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.category_id
                    LEFT JOIN orders o ON p.product_id = o.product_id
                    WHERE p.product_id = ?
                    GROUP BY p.product_id
                ");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product_details = $result->fetch_assoc();
                
                if ($product_details) {
                    echo json_encode($product_details);
                } else {
                    echo json_encode(['error' => 'Product not found']);
                }
                break;

            case 'get_categories':
                $sql = "SELECT c.category_id, c.category_name, COUNT(p.product_id) as product_count 
                        FROM categories c 
                        LEFT JOIN products p ON c.category_id = p.category_id 
                        GROUP BY c.category_id";
                
                $result = $conn->query($sql);
                $categories = [];
                
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
                
                echo json_encode(['categories' => $categories]);
                break;
        }
    }
}

// Add these new POST handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $name = $_POST['product_name'];
                $category_id = $_POST['category_id'];
                $price = $_POST['price'];
                $stock = $_POST['stock_quantity'];

                $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, price, stock_quantity) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sidi", $name, $category_id, $price, $stock);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error adding product']);
                }
                break;

            case 'add_category':
                $name = $_POST['category_name'];
                $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
                $stmt->bind_param("s", $name);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error adding category']);
                }
                break;

            case 'delete_product':
                $product_id = $_POST['product_id'];
                $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
                $stmt->bind_param("i", $product_id);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error deleting product']);
                }
                break;

            case 'delete_category':
                $category_id = $_POST['category_id'];
                
                // Check if category has products
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
                $stmt->bind_param("i", $category_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                
                if ($row['count'] > 0) {
                    echo json_encode(['success' => false, 'error' => 'Cannot delete category with products']);
                    break;
                }
                
                $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
                $stmt->bind_param("i", $category_id);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error deleting category']);
                }
                break;
        }
    }
}

$conn->close();
?>
