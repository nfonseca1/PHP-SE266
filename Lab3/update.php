<?php
require 'database.php';

$id = null;
if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if ( null==$id ) {
    header("Location: index.php");
}

if ( !empty($_POST)) {

    // keep track validation errors
    $corpError = null;
    $emailError = null;
    $ownerError = null;
    $zipcodeError = null;
    $phoneError = null;

    // keep track post values
    $corp = $_POST['corp'];
    $email = $_POST['email'];
    $owner = $_POST['owner'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];

    // validate input
    $valid = true;
    if (empty($corp)) {
        $corpError = 'Please enter Name';
        $valid = false;
    }

    if (empty($email)) {
        $emailError = 'Please enter Email Address';
        $valid = false;
    } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
        $emailError = 'Please enter a valid Email Address';
        $valid = false;
    }

    if (empty($owner)) {
        $ownerError = "Please enter the Owner's name";
        $valid = false;
    }
    if (empty($zipcode)) {
        $zipcodeError = 'Please enter Corp zip code';
        $valid = false;
    }
    if (empty($phone)) {
        $phoneError = 'Please enter phone number';
        $valid = false;
    }

    // update data
    if ($valid) {
        $pdo = dbconn();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE corps  set corp = ?,email = ?, owner = ?, zipcode =?, phone = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($corp,$email,$owner,$zipcode,$phone,$id));
        header("Location: index.php");
    }
} else {
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM corps where id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $corp = $data['corp'];
    $email = $data['email'];
    $owner = $data['owner'];
    $zipcode = $data['zipcode'];
    $phone = $data['phone'];
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
            <h3>Update Corporation</h3>
        </div>

        <form class="form-horizontal" action="update.php?id=<?php echo $id?>" method="post">
            <div class="control-group <?php echo !empty($corpError)?'error':'';?>">
                Name<div class="controls">
                    <input name="corp" type="text"  placeholder="Name" value="<?php echo !empty($corp)?$corp:'';?>">
                    <?php if (!empty($corpError)): ?>
                        <span><?php echo $corpError;?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                Email Address<div class="controls">
                    <input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
                    <?php if (!empty($emailError)): ?>
                        <span><?php echo $emailError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($ownerError)?'error':'';?>">
                Owner<div class="controls">
                    <input name="owner" type="text"  placeholder="Owner" value="<?php echo !empty($owner)?$owner:'';?>">
                    <?php if (!empty($ownerError)): ?>
                        <span><?php echo $ownerError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($zipcodeError)?'error':'';?>">
                Zip Code<div class="controls">
                    <input name="zipcode" type="text"  placeholder="Zip Code" value="<?php echo !empty($zipcode)?$zipcode:'';?>">
                    <?php if (!empty($zipcodeError)): ?>
                        <span><?php echo $zipcodeError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="control-group <?php echo !empty($phoneError)?'error':'';?>">
                Phone Number<div class="controls">
                    <input name="phone" type="text"  placeholder="Phone Number" value="<?php echo !empty($phone)?$phone:'';?>">
                    <?php if (!empty($phoneError)): ?>
                        <span><?php echo $phoneError;?></span>
                    <?php endif;?>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success">Update</button>
                <a class="btn" href="index.php">Back</a>
            </div>
        </form>
    </div>

</div>
</body>
</html>
