<?php
//Category fuctions
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
    echo "<table>";
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
            echo "<a href='checkout.php?addID=" . $product->product_id . "'>Add to Cart</a></p>";
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