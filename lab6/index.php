<?php
session_start();
$_SESSION['isLoggedIn'] = false;

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
<form action="index.php" method="POST" class="form-inline">
    <div class="form-group">
        <label for="category">Category</label>
        <select class="form-control" id="category" name="category">
            <option value="all">All</option>
            <?php
            foreach($categories as $category){
                echo "<option value='$category->category'>"
                    . $category->category . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
    <label for="priceMin">Price Range</label>
    <input type="number" class="form-control" id="priceMin" name="priceMin" placeholder="Min">
    <label>to</label>
    <input type="number" class="form-control" id="priceMax" name="priceMax" placeholder="Max">
    </div>
    <button type="submit" class="btn btn-default" name="filter">Filter Results</button>
</form>
<br/>

<?php
//Filter Results
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
//Get results from search bar
if(isset($_POST['submitSearch']))
{
    $imagePath = 'admin/images/';
    $term = $_POST['search'];

    Search($pdo, $term, $imagePath);
}

include('includes/footer.php');
?>
