<?php
require 'database.php';
$id = null;
if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if ( null==$id ) {
    header("Location: index.php");
} else {
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM corps where id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
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
            <h3>Corporation Information</h3>
        </div>
        <table>
            <tr><td>DATE CREATED</td><td> <?php echo $data['incorp_dt'];?></td></tr>
            <tr><td>CORP NAME</td><td> <?php echo $data['corp'];?></td></tr>
            <tr><td>CORP EMAIL</td><td> <?php echo $data['email'];?></td></tr>
            <tr><td>ZIP CODE</td><td> <?php echo $data['zipcode'];?></td></tr>
            <tr><td>CORP OWNER</td><td> <?php echo $data['owner'];?></td></tr>
            <tr><td>PHONE NUMBER</td><td> <?php echo $data['phone'];?></td></tr>
        </table>

            <div class="form-actions">
                <a class="btn" href="index.php">Back</a>
            </div>


        </div>
    </div>

</div>
</body>
</html>