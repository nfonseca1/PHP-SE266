<?php
date_default_timezone_set('EST');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include('bootstrap.html');
    ?>
    <meta charset="utf-8">
    <title>Horizon</title>
</head>

<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Horizon</a>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <form action="index.php" method="POST" class="navbar-form navbar-left">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" name="submitSearch" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="orders.php">Orders</a></li>
                    <?php
                    if (!$_SESSION['isLoggedIn'])
                    {
                        $_SESSION['items'] = 0;
                        if (isset($_SESSION['cart']))
                        {
                            foreach($_SESSION['cart'] as $item)
                            {
                                $_SESSION['items']++;
                            }
                        }
                        echo "<li><a href='cart.php'>Cart(".$_SESSION['items'].")</a></li>";
                    }
                    if($_SESSION['isLoggedIn'] || $_SESSION['userLoggedIn'])
                    {
                        echo "<li><a href='register.php'>Logout</a></li>";
                    }
                    else {
                        echo "<li><a href='register.php'>Login</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <br/>