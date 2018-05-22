<?php

function Delete($pdo, $cat)
{
    $sql = "DELETE FROM categories WHERE category = ?";
    $stmt = $pdo->prepare($sql);
    if(!$stmt->execute(array($_POST['category']))){
        echo "Error Deleting Category";
    }
    else {
        header("Location: index.php");
    }
}

function Add($pdo, $catText)
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

function Update($pdo, $cat, $catText)
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


function ProductAdd($pdo, $cat, $product, $price, $image)
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