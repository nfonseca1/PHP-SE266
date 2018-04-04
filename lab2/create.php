<?php

require 'database.php';

if ( !empty($_POST))
{
    // keep track post values
    $corp = $_POST['corp'];
    $email = $_POST['email'];
    $owner = $_POST['owner'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];

    // insert data
    echo $corp;
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO corps (corp,incorp_dt,email,zipcode,owner,phone) values(?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($corp,date('Y-m-d H:i:s'),$email,$zipcode,$owner,$phone));
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
<div class="container">

    <div>
        <div class="row">
            <h3>Create a Corporation</h3>
        </div>

        <form action="create.php" method="post">
            Corporation
                <input name="corp" type="text"  placeholder="Name" >
            <br/>
            Email
                <input name="email" type="text"  placeholder="Name" >
            <br/>
            Zip Code
                <input name="zipcode" type="text"  placeholder="Name" >
            <br/>
            Owner
                <input name="owner" type="text"  placeholder="Name" >
            <br/>
            Phone
                <input name="phone" type="text"  placeholder="Name" >
            <br/>
            <button type="submit" class="btn btn-success">Create</button>
            <a class="btn" href="index.php">Back</a>
        </form>
    </div>

</div>
</body>
</html>