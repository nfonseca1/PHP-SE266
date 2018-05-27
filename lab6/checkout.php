<?php
session_start();
include 'includes/database.php';
include 'includes/header.php';
include 'includes/handler.php';
//Make sure they're logged in, otherwise, redirect
if (!$_SESSION['userLoggedIn']){
    echo "You are not logged in";
    $_SESSION['redirect'] = "checkout.php";
    header("Location: register.php");
}
//Store temporary vars to hold orderItem info
$_SESSION['total'] = 0;
$_SESSION['taxAmount'] = 0.07;
$_SESSION['shipping'] = 0.00;
$states = array(
    "AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC",
    "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA",
    "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE",
    "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC",
    "SD", "TN", "TX", "UT", "VA", "VT", "WA", "WI", "WV", "WY");
$pdo = dbConn();

?>

<h2>Order Summary:</h2>

<?php
//Add the prices together to get total and calculate tax
foreach($_SESSION['cart'] as $orderItem => $orderQty)
{
    $sql = 'SELECT * FROM products WHERE product_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderItem]);
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    $_SESSION['total'] = $_SESSION['total'] + $product->price * $orderQty;
}

$_SESSION['tax'] = $_SESSION['total'] * $_SESSION['taxAmount'];
$_SESSION['tax'] = round($_SESSION['tax'], 2);
?>

<table class="table table-striped">
    <tr>
        <td><h3>Items: </h3></h3></td>
        <td><h3>$<?php echo $_SESSION['total']; ?></h3></td>
    </tr>
    <tr>
        <td><h3>Tax: </h3></h3></td>
        <td><h3>$<?php echo $_SESSION['tax']; ?></h3></td>
    </tr>
    <tr>
        <td><h3>Shipping: </h3></h3></td>
        <td><h3>$<?php echo $_SESSION['shipping']; ?></h3></td>
    </tr>
    <tr>
        <td><h3>Total: </h3></h3></td>
        <td><h3>$<?php echo $_SESSION['total'] + $_SESSION['tax'] + $_SESSION['shipping']; ?></h3></td>
    </tr>
</table>
<br/>
<form class="form-inline" action="orders.php" method="POST">
    <h2>Address: </h2>
    <div class="form-group">
    <label for="street">Street </label>
    <input class="form-control" type="text" id="street" name="street" placeholder="Type anything in here" required>
    <label for="city">City </label>
    <input class="form-control" type="text" id="city" name="city" placeholder="Type anything in here" requirec>
    <label for="state">State </label>
    <select class="form-control" id="state" name="state">
        <?php
            foreach($states as $state){
                echo "<option>$state</option>\n";
            }
        ?>
    </select>
    <label for="zip">Zip Code </label>
    <input class="form-control" type="text" id="zip" name="zip" placeholder="Type anything in here" requirec>
    </div>

    <h2>Payment: </h2>
    <div class="form-group">
    <label for="cardNumber">Credit Card Number</label>
    <input class="form-control" type="number" id="cardNumber" name="cardNumber" placeholder="Type anything in here" requirec>
    <label for="cvc">CVC</label>
    <input class="form-control" type="number" id="cvc" name="cvc" placeholder="Type anything in here" requirec>
    <label for="date">Expiration Date</label>
    <input class="form-control" type="text" id="date" name="date" placeholder="Type anything in here" requirec>
    </div>

    <p><button class="btn btn-primary" type="submit" name="placeOrder">Place Order</button></p>
</form>
<?php

include('includes/footer.php');
?>
