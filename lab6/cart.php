<?php
session_start();
include 'includes/database.php';
include 'includes/header.php';
include 'includes/handler.php';

if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}
$_SESSION['redirect'] = "index.php";

$pdo = dbConn();

?>

    <h2>Cart:</h2>

<?php

if(!empty($_GET['addID']))
{
    $id = $_REQUEST['addID'];

    if(array_key_exists($id, $_SESSION['cart']))
    {
        $_SESSION['cart'][$id]++;
    }
    else {
        $_SESSION['cart'][$id] = 1;
    }
}

if(!empty($_GET['remove']))
{
    $id = $_REQUEST['remove'];

    unset($_SESSION['cart'][$id]);
}

foreach($_SESSION['cart'] as $orderItem => $orderQty)
{
    $sql = 'SELECT * FROM products WHERE product_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderItem]);
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    echo "<table><tr>";
    echo "<td><img src='admin/images/" . $product->image . "' style='height: 150px;'></td>";
    echo "<td><h3>" . $product->product . "</h3></td>";
    echo "<td><h3>$" . $product->price . "</h3></td>";
    echo "<td><h3>Qty.</h3><h2>" . $orderQty . "</h2></td>";
    echo "<td><a href='checkout.php?remove=$orderItem'>Remove</a></td>";
    echo "</tr></table>";
}
?>
<form action="checkout.php" method="GET">
    <button type="submit" name="checkout">Proceed to Checkout</button>
</form>
<?php

include('includes/footer.php');
?>
