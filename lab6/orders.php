<?php
session_start();
include 'includes/database.php';
include 'includes/header.php';
include 'includes/handler.php';
//Make sure they're logged in, otherwise, redirect
if (!$_SESSION['userLoggedIn']){
    echo "You are not logged in";
    $_SESSION['redirect'] = "orders.php";
    header("Location: register.php");
}
//Select our customer now to use for product info
$pdo = dbConn();

$sql = 'SELECT * FROM customers WHERE email = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['email']]);
$customer = $stmt->fetch(PDO::FETCH_OBJ);
$customerID = $customer->customer_id;
//Create a new order
if(isset($_POST['placeOrder']))
{
    //Create order and temporarily set tax to 0 so it can be selected after inserting
    $sql = "INSERT INTO orders (customer_id, order_date, shipping_date, tax) values(?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$customerID, date('Y-m-d H:i:s'), date('Y-m-d'), 0])){
        echo "Error Adding order";
    } else {
        //header("Location: " . $_SESSION['redirect']);
    }
    $sql = "SELECT order_id FROM orders WHERE tax = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $order = $stmt->fetch();
    $orderID = $order['order_id'];
    //Add each cart item to order item with correct order id
    foreach($_SESSION['cart'] as $orderItem => $orderQty)
    {

        $sql = "INSERT INTO orderItems (order_id, product_id, quantity) values(?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if(!$stmt->execute([$orderID, $orderItem, $orderQty])){
            echo "Error Adding order";
        }
    }
    //Set that order's tax back to appropriate amount
    $sql = "UPDATE orders set tax = ? WHERE customer_id = ?";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$_SESSION['taxAmount'], $customerID])){
        echo "Error Updating Category";
    }
}

?>

    <h2>Past Orders:</h2>

<?php
//Select orders for this customer
$sql = "SELECT * FROM orders WHERE customer_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$customerID]);
$orders = $stmt->fetchAll();
//For each order, create table and show order info
foreach($orders as $order)
{
    echo "<table class='table table-striped'>";
    echo "<tr><td><h3>Ordered On: " . $order['order_date'] . "</h3></td>";
    echo "<td><h3>Shipped On: " . $order['shipping_date'] . "</h3></td>";
    echo "<td><h3>Tax: " . $order['tax']*100 . "%</h3></td></tr>";

    $sql = "SELECT * FROM orderItems WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order['order_id']]);
    $orderItems = $stmt->fetchAll();
    //Show each product from that order
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
    echo "</table><br/>";
}

include('includes/footer.php');
?>