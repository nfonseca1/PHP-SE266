<?php
session_start();
include 'includes/database.php';
include 'includes/header.php';
include 'includes/handler.php';

$pdo = dbConn();
$_SESSION['redirect'] = "index.php";

//Get all categories to display in drop down
$pdo = dbConn();
$sql = 'SELECT category FROM categories';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<h3>Filter:</h3>
<form action="index.php" method="POST">
    <label for="category">Category</label>
    <select id="category" name="category">
        <option value="all">All</option>
        <?php
        foreach($categories as $category){
            echo "<option value='$category->category'>"
                . $category->category . "</option>";
        }
        ?>
    </select>
    <label for="priceMin">Price Range</label>
    <input type="number" id="priceMin" name="priceMin" placeholder="Min">
    <span> to </span>
    <input type="number" id="priceMax" name="priceMax" placeholder="Max">
    <button type="submit" name="filter">Filter Results</button>
</form>

<?php

if(isset($_POST['filter']))
{
    $priceMin = 0;
    if($_POST['priceMin']!=""){$priceMin = $_POST['priceMin'];}
    $priceMax = 999999;
    if($_POST['priceMax']!=""){$priceMax = $_POST['priceMax'];}
    $cat = $_POST['category'];
    $imagePath = 'admin/images/';

    Filter($pdo, $cat, $priceMin, $priceMax, $imagePath);
}

include('includes/footer.php');
?>
