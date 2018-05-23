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
    DeleteCategory($pdo, $_POST['category']);
}

if(isset($_POST['add']))
{
    AddCategory($pdo, $_POST['categoryText']);
}

if(isset($_POST['update']))
{
    UpdateCategory($pdo, $_POST['category'], $_POST['categoryText']);
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
        $file_split = explode('.',$_FILES['image']['name']);
        $file_ext = strtolower(end($file_split));
        $extensions = array("jpeg","jpg","png");

        if(in_array($file_ext, $extensions)){
            move_uploaded_file($file_tmp,"images/".$file_name);
        }
        else {
            echo "Image extension must be jpg, jpeg or png.";
        }
    }
    AddProduct($pdo, $cat, $product, $price, $file_name);
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

<form action="index.php" method="POST">
    <button type="submit" name="addProduct">Add New Product</button>
</form>
<?php
//Create add product form when clicking 'add product'
if(isset($_POST['addProduct']))
{
    CreateAddProductForm($pdo);
}

if(isset($_POST['filter']))
{
    $priceMin = 0;
    if($_POST['priceMin']!=""){$priceMin = $_POST['priceMin'];}
    $priceMax = 999999;
    if($_POST['priceMax']!=""){$priceMax = $_POST['priceMax'];}
    $cat = $_POST['category'];

    Filter($pdo, $cat, $priceMin, $priceMax);
}

if ( !empty($_GET['updateID'])) {

    $id = $_REQUEST['updateID'];

    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    $sql = 'SELECT * FROM categories WHERE category_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product->category_id]);
    $defaultCat = $stmt->fetch(PDO::FETCH_OBJ);

    echo "<form action='index.php' method='post' enctype='multipart/form-data'>";
    echo "<label for='category'>Category</label>";
    echo "<select id='category' name='category'>";

    $sql = 'SELECT category FROM categories';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach($categories as $category){
        echo "<option value='$category->category'";
        if($category->category == $defaultCat->category){
            echo "selected='selected'";
        }
        echo ">" . $category->category . "</option>";
    }
    echo "</select>";
    echo "<label for='product'>Product</label>";
    echo "<input type='text' id='product' name='product' value='$product->product' required>";
    echo "<label for='price'>Price</label>";
    echo "<input type='text' id='price' name='price' value='$product->price' required>";
    echo "<label for='image'>Image</label>";
    echo "<input type='file' id='image' name='image'>";
    echo "<input type='hidden' name = 'updateID' value='$id'>";
    echo "<button type='submit' name='submitUpdate'>Update Product</button>";
    echo "</form>";
}

if(isset($_POST['submitUpdate']))
{
    $id = $_POST['updateID'];
    $cat = $_POST['category'];
    $product = $_POST['product'];
    $price = $_POST['price'];
    if($_FILES['image']['name'] != "")
    {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_split = explode('.',$_FILES['image']['name']);
        $file_ext = strtolower(end($file_split));
        $extensions = array("jpeg","jpg","png");

        if(in_array($file_ext, $extensions)){
            move_uploaded_file($file_tmp,"images/".$file_name);
        }
        else {
            echo "Image extension must be jpg, jpeg or png.";
        }
        UpdateProduct($pdo, $id, $cat, $product, $price, $file_name);
    }
    else {
        UpdateProduct($pdo, $id, $cat, $product, $price);
    }

}

if ( !empty($_GET['deleteID']))
{
    $id = $_REQUEST['deleteID'];

    DeleteProduct($pdo, $id);
}

include('../includes/footer.php');
?>
