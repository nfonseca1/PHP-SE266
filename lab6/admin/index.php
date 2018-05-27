<?php
session_start();
//Make sure admin is logged in, otherwise, redirect
if (!$_SESSION['isLoggedIn']){
    echo "You are not logged in as an admin";
    header("Location: register.php");
}
else {
    $_SESSION['userLoggedIn'] = false;
}

include '../includes/database.php';
include '../includes/header.php';
include '../includes/handler.php';

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

//Add product to database
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

<form class="form-inline" action="index.php" method="POST">
    <div class="form-group">
        <label for="category">Category</label>
        <select class="form-control" id="category" name="category">
    </div>
        <?php
        foreach($categories as $category){
            echo "<option value='$category->category'>"
            . $category->category . "</option>";
        }
        ?>
    </select>
    <button class="btn btn-danger" type="submit" name="delete">Delete</button>
    <input class="form-control" type="text" id="categoryText" name="categoryText" placeholder="Category">
    <button class="btn btn-success" type="submit" name="add">Add</button>
    <button class="btn btn-info" type="submit" name="update">Update</button>
</form>

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
</br>
<form action="index.php" method="POST">
    <button class="btn btn-primary" type="submit" name="addProduct">Add New Product</button>
</form>
</br>
<?php
//Create add product form when clicking 'add product'
if(isset($_POST['addProduct']))
{
    CreateAddProductForm($pdo);
}
//Filter Results
if(isset($_POST['filter']))
{
    $priceMin = 0;
    if($_POST['priceMin']!=""){$priceMin = $_POST['priceMin'];}
    $priceMax = 999999;
    if($_POST['priceMax']!=""){$priceMax = $_POST['priceMax'];}
    $cat = $_POST['category'];
    $imagePath = 'images/';
    $isAdmin = $_SESSION['isLoggedIn'];

    Filter($pdo, $cat, $priceMin, $priceMax, $imagePath, $isAdmin);
}
//Get results from search bar
if(isset($_POST['submitSearch']))
{
    $imagePath = 'images/';
    $isAdmin = $_SESSION['isLoggedIn'];
    $term = $_POST['search'];

    Search($pdo, $term, $imagePath, $isAdmin);
}
//Setup product update form when clicking "update"
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

    echo "<form class='form-inline' action='index.php' method='post' enctype='multipart/form-data'>";
    echo "<div class='form-group'>";
    echo "<label for='category'>Category</label>";
    echo "<select class='form-control' id='category' name='category'>";

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
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='product'>Product</label>";
    echo "<input class='form-control' type='text' id='product' name='product' value='$product->product' required>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='price'>Price</label>";
    echo "<input class='form-control' type='text' id='price' name='price' value='$product->price' required>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='image'>Image</label>";
    echo "<input class='form-control' type='file' id='image' name='image'>";
    echo "<input type='hidden' name = 'updateID' value='$id'>";
    echo "</div>";
    echo "<button class='btn btn-info' type='submit' name='submitUpdate'>Update Product</button>";
    echo "</form>";
}
//Update product in database
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
//Delete product from database
if ( !empty($_GET['deleteID']))
{
    $id = $_REQUEST['deleteID'];

    DeleteProduct($pdo, $id);
}

include('../includes/footer.php');
?>
