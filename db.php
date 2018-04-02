<?php
$dsn = "mysql:host=localhost;dbname=phpclassspring2018";
$userName = "PHPClassSpring2018";
$pass = "SE266";

try
{
    $db = new PDO($dsn, $userName, $pass);
}
catch (PDOException $e)
{
    die("Cannot connect to database");
}