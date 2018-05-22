<?php
session_start();

include '../includes/database.php';
include '../includes/header.php';
include 'handler.php';

if (!$_SESSION['isLoggedIn']){
    echo "You are not logged in as an admin";
    header("Location: register.php");
}

//Get all categories to display in drop down
$pdo = dbConn();
$sql = 'SELECT category FROM categories';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_OBJ);

//Handle CRUD form for Categories
if(isset($_POST['delete']))
{
    Delete($pdo, $_POST['category']);
}

if(isset($_POST['add']))
{
    Add($pdo, $_POST['categoryText']);
}

if(isset($_POST['update']))
{
    Update($pdo, $_POST['category'], $_POST['categoryText']);
}

//Handle product CRUD
if(isset($_POST['submitAdd']))
{
    $cat = $_POST['category'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    $file_name = "";
    if(isset($_FILES['image']))
    {
        $file_name = $_FILES['image']['name'];
        $file_tmp =$_FILES['image']['tmp_name'];
        move_uploaded_file($file_tmp,"images/".$file_name);
    }
    ProductAdd($pdo, $cat, $product, $price, $file_name);
}
?>

<form action="index.php" method="POST">
    <label for="category">Category</label>
    <select id="category" name="category">
        <?php
        foreach($categories as $category){
            echo "<option value='$category->category'>"
            . $category->category . "</option>";
        }
        ?>
    </select>
    <button type="submit" name="delete">Delete</button>
    <input type="text" id="categoryText" name="categoryText">
    <button type="submit" name="add">Add</button>
    <button type="submit" name="update">Update</button>
</form>
<form action="index.php" method="POST">
    <button type="submit" name="addProduct">Add Product</button>
</form>
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
//Create add product form when clicking 'add product'
if(isset($_POST['addProduct']))
{
    echo "<form action='index.php' method='post' enctype='multipart/form-data'>";
    echo "<label for='category'>Category</label>";
    echo "<select id='category' name='category'>";

    $sql = 'SELECT category FROM categories';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach($categories as $category){
        echo "<option value='$category->category'>"
            . $category->category . "</option>";
    }
    echo "</select>";
    echo "<label for='product'>Product</label>";
    echo "<input type='text' id='product' name='product' required>";
    echo "<label for='price'>Price</label>";
    echo "<input type='text' id='price' name='price' required>";
    echo "<label for='image'>Image</label>";
    echo "<input type='file' id='image' name='image'>";
    echo "<button type='submit' name='submitAdd'>Add Product</button>";
    echo "</form>";
}

if(isset($_POST['filter']))
{
    $priceMin = 0;
    if(isset($_POST['priceMin'])){$priceMin = $_POST['priceMin'];}
    $priceMax = 999999;
    if(isset($_POST['priceMax'])){$priceMax = $_POST['priceMax'];}
    $cat = $_POST['category'];

    if($_POST['category']=="all"){
        $sql = 'SELECT * FROM products WHERE price BETWEEN ? AND ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$priceMin, $priceMax]);
    }
    else {
        $sql = 'SELECT * FROM categories WHERE category = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cat]);
        $category = $stmt->fetch(PDO::FETCH_OBJ);
        $catID = $category->category_id;

        $sql = 'SELECT * FROM products WHERE category_id = ? && price BETWEEN ? AND ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$catID, $priceMin, $priceMax]);
    }
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "<table>";
    foreach($products as $product)
    {
        echo "<tr>";
        echo "<td><img src='images." . $product->image . "'></td>";
        echo "<td>" . $product->product . "</td>";
        echo "<td>" . $product->price . "</td>";

        echo "</tr>";
    }
    echo "</table>";
}

include('../includes/footer.php');
?>
