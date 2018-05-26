<?php
session_start();
include 'includes/database.php';
include 'includes/header.php';
include 'includes/handler.php';

if (!$_SESSION['userLoggedIn']){
    echo "You are not logged in";
    $_SESSION['redirect'] = "checkout.php";
    header("Location: register.php");
}

$pdo = dbConn();

?>

    <h2>Order Summary:</h2>

<?php

include('includes/footer.php');
?>