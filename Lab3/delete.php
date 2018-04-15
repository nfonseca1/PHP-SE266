<?php
require 'database.php';
$id = 0;

if ( !empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if ( !empty($_POST)) {
    // keep track post values
    $id = $_POST['id'];

    // delete data
    $pdo = dbconn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM corps  WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
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
            <h3>Delete a Corporation</h3>
        </div>

        <form action="delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
            <p>Are you sure to delete ?</p>
            <div>
                <button type="submit" class="btn btn-danger">Yes</button>
                <a class="btn" href="index.php">No</a>
            </div>
        </form>
    </div>

</div>
</body>
</html>