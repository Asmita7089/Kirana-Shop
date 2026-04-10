<?php

// DASHBOARD FUNCTIONS
function getTodaySales($user_id, $conn) {
    $today = date('Y-m-d');
    $sql = "SELECT COUNT(*) as count, SUM(total_amount) as revenue, SUM(qty_sold) as items 
            FROM sales WHERE user_id = ? AND sale_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// LOW STOCK ALERTS
function getLowStock($user_id, $conn) {
    $sql = "SELECT id, product_name, qty_stock, min_stock, price_sell 
            FROM products WHERE user_id = ? AND qty_stock <= min_stock AND active = 1
            ORDER BY qty_stock ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// RUNNING OUT SOON (Based on sales velocity)
function getRunningOut($user_id, $conn) {
    $sql = "SELECT p.id, p.product_name, p.qty_stock,
            COUNT(s.id) as sales_count,
            ROUND(p.qty_stock / (COUNT(s.id) / 30), 1) as days_left
            FROM products p
            LEFT JOIN sales s ON p.id = s.product_id AND s.user_id = p.user_id
            AND s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            WHERE p.user_id = ? AND p.active = 1
            GROUP BY p.id
            HAVING days_left <= 7 AND days_left > 0
            ORDER BY days_left ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// TOP SELLERS
function getTopSellers($user_id, $conn) {
    $sql = "SELECT p.product_name, SUM(s.qty_sold) as total_sold, SUM(s.total_amount) as revenue
            FROM sales s
            JOIN products p ON s.product_id = p.id
            WHERE s.user_id = ? AND s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY s.product_id
            ORDER BY total_sold DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// SLOW MOVING (Dead Stock)
function getSlowMoving($user_id, $conn) {
    $sql = "SELECT p.product_name, p.qty_stock, p.price_cost, COUNT(s.id) as sales_count,
            MAX(s.sale_date) as last_sale
            FROM products p
            LEFT JOIN sales s ON p.id = s.product_id AND s.user_id = p.user_id
            AND s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            WHERE p.user_id = ? AND p.active = 1
            GROUP BY p.id
            HAVING sales_count <= 3 AND p.qty_stock > 0
            ORDER BY sales_count ASC LIMIT 15";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// COMBO SUGGESTIONS
function getComboSuggestions($user_id, $conn) {
    $sql = "SELECT p.product_name, COUNT(s.id) as sales,
            CASE
                WHEN p.product_name LIKE '%bread%' THEN 'butter, milk'
                WHEN p.product_name LIKE '%milk%' THEN 'bread, biscuits'
                WHEN p.product_name LIKE '%rice%' THEN 'dal, salt'
                WHEN p.product_name LIKE '%oil%' THEN 'salt, spices'
                ELSE 'complementary items'
            END as suggestion
            FROM products p
            LEFT JOIN sales s ON p.id = s.product_id AND s.user_id = p.user_id
            WHERE p.user_id = ? AND p.active = 1
            GROUP BY p.id
            HAVING sales > 0
            ORDER BY sales DESC LIMIT 8";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// CUSTOMER REQUESTS
function getUnmetRequests($user_id, $conn) {
    $sql = "SELECT id, product_name, req_count, req_date 
            FROM customer_requests 
            WHERE user_id = ? AND is_stocked = 0
            ORDER BY req_count DESC, req_date DESC LIMIT 20";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// RESTOCK SUGGESTIONS
function getRestockList($user_id, $conn) {
    $sql = "SELECT id, product_name, qty_stock, min_stock, price_cost, max_stock
            FROM products 
            WHERE user_id = ? AND qty_stock <= min_stock AND active = 1
            ORDER BY qty_stock ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// UTILITY FUNCTIONS
function fmt_currency($amount) {
    return '₹' . number_format($amount, 2);
}

function fmt_date($date) {
    return date('d M Y', strtotime($date));
}

function fmt_time($time) {
    return date('g:i A', strtotime($time));
}

function sanitize($input) {
    global $conn; // This pulls in your connection from config.php
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return mysqli_real_escape_string($conn, $data);
}

function countProducts($user_id, $conn) {
    $sql = "SELECT COUNT(*) as total FROM products WHERE user_id = ? AND active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}
?>