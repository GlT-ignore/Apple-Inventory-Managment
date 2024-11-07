<?php
// view_tables.php

// Database connection
$servername = "localhost"; // your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "inventorymanagement"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products including stock_quantity
$products_result = $conn->query("SELECT product_id, product_name, price, stock_quantity FROM products"); // Updated to use stock_quantity
$categories_result = $conn->query("SELECT * FROM categories");
$orders_result = $conn->query("SELECT * FROM orders");

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tables</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .refresh-button {
            margin-bottom: 20px;
            float: right;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .refresh-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<button class="refresh-button" onclick="location.reload();">Refresh</button>

<h1>Products Table</h1>
<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Stock Quantity</th> <!-- Updated column header -->
    </tr>
    <?php if ($products_result->num_rows > 0): ?>
        <?php while ($row = $products_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['stock_quantity']; ?></td> <!-- Displaying stock_quantity -->
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No products found</td>
        </tr>
    <?php endif; ?>
</table>

<h1>Categories Table</h1>
<table>
    <tr>
        <th>Category ID</th>
        <th>Category Name</th>
    </tr>
    <?php if ($categories_result->num_rows > 0): ?>
        <?php while ($row = $categories_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['category_id']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No categories found</td>
        </tr>
    <?php endif; ?>
</table>

<h1>Orders Table</h1>
<table>
    <tr>
        <th>Order ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Order Date</th>
    </tr>
    <?php if ($orders_result->num_rows > 0): ?>
        <?php while ($row = $orders_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo $row['order_quantity']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No orders found</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
