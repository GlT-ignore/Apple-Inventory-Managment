
# E-commerce Inventory Management System 🛒

A modern, real-time inventory management system with a luxury-themed, responsive interface designed for seamless product and order management.

---

## Features ✨

### 📦 Product Management
- **Real-time Tracking**: Instantly update stock levels and product information.
- **CRUD Operations**: Add, edit, and delete products easily.
- **Stock Level Monitoring**: Keep an eye on inventory levels at all times.
- **Low Stock Alerts**: Notifications for low inventory to avoid stockouts.
- **Product Categorization**: Organize products by categories for easy management.

### 🛍️ Order Processing
- **Real-time Order Placement**: Smooth order placement process.
- **Stock Validation**: Prevents orders from exceeding available stock.
- **Order Confirmation System**: Confirms orders in real time.
- **Transaction Management**: Tracks each transaction for accurate records.

### 🎨 User Interface
- **Responsive Design**: Mobile-friendly and desktop compatible.
- **Interactive Animations**: Engaging transitions and animations.
- **Toast Notifications**: Updates with subtle notifications.
- **Optimized for Touch and Mobile**: Intuitive interface on all devices.

---

## Tech Stack 🖥️

**Frontend:**
- HTML5
- CSS3
- JavaScript (ES6+)
- Bootstrap 5
- Bootstrap Icons
- Google Fonts

**Backend:**
- PHP
- MySQL
- JSON API for streamlined data handling

---

## Database Schema 📊

```sql
-- Database: inventorymanagement

CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    order_quantity INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
```

---

## Setup Instructions 🚀

1. **Clone the Repository**
   ```bash
   git clone https://github.com/GlT-ignore/Apple-Inventory-Managment.git
   cd Apple-Inventory-Managment
   ```

2. **Import Database Schema**
   - Import the SQL schema into your MySQL database via phpMyAdmin, MySQL Workbench, or any SQL client.

3. **Configure Database Connection**
   - Open `ecommerce.php` and enter your database connection details.

4. **Deploy**
   - Deploy the project to a PHP-compatible server (e.g., Apache) and test the setup.

---

## Project Structure 📁

```plaintext
inventory-management/
├── assets/
│   ├── css/
│   │   └── login.css
│   └── images/
│       ├── logo.png
│       └── fav.png
├── index.html
├── ecommerce.php
└── README.md
```

---

## Contributing 🤝

Contributions are welcome! Please fork this repository and submit a pull request for major changes, or open an issue to discuss potential improvements.

---

## License 📝

This project is licensed under the [MIT License](LICENSE).

---

Enjoy managing your inventory with ease! 🛒
