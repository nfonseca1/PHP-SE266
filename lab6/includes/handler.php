<?php
//Category functions
function DeleteCategory($pdo, $cat)
{
    $sql = "DELETE FROM categories WHERE category = ?";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$cat])){
        echo "Error Deleting Category";
    }
    else {
        header("Location: index.php");
    }
}

function AddCategory($pdo, $catText)
{
    $sql = 'SELECT category FROM categories WHERE category = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$catText]);
    if($categ = $stmt->fetch(PDO::FETCH_OBJ))
    {
        echo "Category already exists";
    }
    else {
        $sql = "INSERT INTO categories (category) values(?)";
        $stmt = $pdo->prepare($sql);
        if(!$stmt->execute([$catText])){
            echo "Error Adding Category";
        }
        else {
            header("Location: index.php");
        }
    }
}

function UpdateCategory($pdo, $cat, $catText)
{
    $sql = "UPDATE categories set category = ? WHERE category = ?";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$catText, $cat])){
        echo "Error Updating Category";
    }
    else {
        header("Location: index.php");
    }
}

//Product functions
function CreateAddProductForm($pdo)
{
    echo "<form action='../admin/index.php' method='post' enctype='multipart/form-data'>";
    echo "<div class='form-group'>";
    echo "<label for='category'>Category</label>";
    echo "<select class='form-control' id='category' name='category'>";

    $sql = 'SELECT category FROM categories';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach($categories as $category){
        echo "<option value='$category->category'>"
            . $category->category . "</option>";
    }
    echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='product'>Product</label>";
    echo "<input class='form-control' type='text' id='product' name='product' required>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='price'>Price</label>";
    echo "<input class='form-control' type='text' id='price' name='price' required>";
    echo "</div>";
    echo "<div class='form-group'>";
    echo "<label for='image'>Image</label>";
    echo "<input class='form-control' type='file' id='image' name='image'>";
    echo "</div>";
    echo "<button class='btn btn-primary' type='submit' name='submitAdd'>Add Product</button>";
    echo "</form>";
}

function AddProduct($pdo, $cat, $product, $price, $image)
{
    $sql = 'SELECT * FROM categories WHERE category = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);
    $category = $stmt->fetch(PDO::FETCH_OBJ);
    $catID = $category->category_id;

    $sql = "INSERT INTO products (category_id, product, price, image) values(?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$catID, $product, $price, $image])){
        echo "Error Adding Product";
    } else {
        header("Location: index.php");
    }
}

function Filter($pdo, $cat, $priceMin, $priceMax, $path, $isAdmin = false)
{
    if($cat == "all")
    {
        $sql = 'SELECT * FROM products WHERE price BETWEEN ? AND ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$priceMin, $priceMax]);
    } else {
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

    DisplayProducts($products, $path, $isAdmin);
}

function Search($pdo, $term, $path, $isAdmin = false)
{
    $sql = "SELECT * FROM products WHERE product LIKE " . "'%" . $term . "%'" . " ORDER BY product_id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_OBJ);

    DisplayProducts($products, $path, $isAdmin);
}

function DisplayProducts($products, $path, $isAdmin)
{
    echo "<table class='table table-striped'>";
    echo "<tr><td>Preview</td><td>Product</td><td>Price</td><td></td></tr>";
    foreach($products as $product)
    {
        echo "<tr>";
        echo "<td><img src='$path" . $product->image . "' style='height: 150px;'></td>";
        echo "<td><h3>" . $product->product . "</h3></td>";
        echo "<td><h3>$" . $product->price . "</h3></td>";
        if ($isAdmin)
        {
            echo "<td>";
            echo "<p><a href='index.php?updateID=".$product->product_id."'>Update</a></p>";
            echo "<p><a href='index.php?deleteID=".$product->product_id."'>Delete</a></p>";
            echo "</td>";
        }
        else {
            echo "<td>";
            echo "<a href='cart.php?addID=" . $product->product_id . "'>Add to Cart</a></p>";
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function UpdateProduct($pdo, $id, $cat, $product, $price, $image = NULL)
{
    $sql = 'SELECT * FROM categories WHERE category = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);
    $category = $stmt->fetch(PDO::FETCH_OBJ);
    $catID = $category->category_id;

    if ($image == NULL) {
        $sql = "UPDATE products set category_id = ?, product = ?, price = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$catID, $product, $price, $id]);
    }
    else {
        $sql = "UPDATE products set category_id = ?, product = ?, price = ?, image = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$catID, $product, $price, $image, $id]);
    }
    if(!$result){
        echo "Error Updating Category";
    }
    else {
        header("Location: index.php");
    }
}

function DeleteProduct($pdo, $id)
{
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute([$id])){
        echo "Error Deleting Product";
    }
    else {
        header("Location: index.php");
    }
}

// Validation
function ValidateIdenticalEmails($email, $email2)
{
    if($email == $email2) {
        return true;
    }
    else {
        return false;
    }
}

function ValidateEmail($email)
{
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return true;
    }
    else {
        return false;
    }
}

function ValidateIdenticalPasswords($password, $password2)
{
    if($password == $password2) {
        return true;
    }
    else {
        return false;
    }
}

function ValidatePassword($password)
{
    if(strlen($password) > 3)
    {
        return true;
    }
    else {
        return false;
    }
}

function ValidateRegister($email, $email2, $password, $password2)
{
    if(ValidateIdenticalEmails($email, $email2)) {
        if(ValidateEmail($email)) {
            if(ValidateIdenticalPasswords($password, $password2)) {
                if(ValidatePassword($password)) {
                    return true;
                }
                else {
                    echo "Password must be more than 3 characters";
                }
            }
            else {
                echo "Passwords do not match";
            }
        }
        else {
            echo "Email is not valid";
        }
    }
    else {
        echo "Emails do not match";
    }
    return false;
}
