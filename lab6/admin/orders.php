<?php
session_start();
include '../includes/database.php';
include '../includes/header.php';
include '../includes/handler.php';
//Make sure admin is logged in, otherwise, redirect
if (!$_SESSION['isLoggedIn']){
    echo "You are not logged in as an admin";
    header("Location: register.php");
}

$pdo = dbConn();

?>

    <h2>Past Orders:</h2>

<?php

$sql = "SELECT * FROM orders";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll();

//Select all existing orders
foreach($orders as $order)
{
    $sql = "SELECT * FROM customers WHERE customer_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order['customer_id']]);
    $customer = $stmt->fetch();

    echo "<table class='table table-striped'>";
    echo "<tr><td><h3>Customer: " . $customer['email'] . "</h3></td>";
    echo "<td><h3>Ordered On: " . $order['order_date'] . "</h3></td><td></td></tr>";
    echo "<tr><td><h3>Shipped On: " . $order['shipping_date'] . "</h3></td>";
    echo "<td><h3>Tax: " . $order['tax']*100 . "%</h3></td><td></td></tr>";

    $sql = "SELECT * FROM orderItems WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order['order_id']]);
    $orderItems = $stmt->fetchAll();

    foreach($orderItems as $orderItem)
    {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderItem['product_id']]);
        $product = $stmt->fetch();

        echo "<tr><td><h4>" . $product['product'] . "</h4></td>";
        echo "<td><h4>$" . $product['price'] . "</h4></td>";
        echo "<td><h4>Qty. " . $orderItem['quantity'] . "</h4></td></tr>";
    }
    echo "</table><hr></br>";
}

include('../includes/footer.php');
?>