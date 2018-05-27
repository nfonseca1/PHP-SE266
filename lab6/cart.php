<?php
session_start();
include 'includes/database.php';
include 'includes/handler.php';
//create cart variable if it doesn't exist
if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}
$_SESSION['redirect'] = "index.php";

$pdo = dbConn();
//Handle IDs sent to cart
if(!empty($_GET['addID']))
{
    $id = $_REQUEST['addID'];
    //if ID exists, increase count, otherwise set to 1
    if(array_key_exists($id, $_SESSION['cart']))
    {
        $_SESSION['cart'][$id]++;
    }
    else {
        $_SESSION['cart'][$id] = 1;
    }
}
//remove ID from array if remove is clicked
if(!empty($_GET['remove']))
{
    $id = $_REQUEST['remove'];

    unset($_SESSION['cart'][$id]);
}

include 'includes/header.php';
?>

    <h2>Cart:</h2>
    <table class='table table-striped'>
<?php
//For every Cart Item, display a table row
foreach($_SESSION['cart'] as $orderItem => $orderQty)
{
    $sql = 'SELECT * FROM products WHERE product_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderItem]);
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    echo "<tr>";
    echo "<td><img src='admin/images/" . $product->image . "' style='height: 150px;'></td>";
    echo "<td><h3>" . $product->product . "</h3></td>";
    echo "<td><h3>$" . $product->price . "</h3></td>";
    echo "<td><h3>Qty. " . $orderQty . "</h3></td>";
    echo "<td><a href='cart.php?remove=$orderItem'>Remove</a></td>";
    echo "</tr>";
}
?>
    </table>
<form action="checkout.php" method="GET">
    <button type="submit" class="btn btn-primary" name="checkout">Proceed to Checkout</button>
</form>
<?php

include('includes/footer.php');
?>
