<?php
// Create database variables and try to return a connection
function dbConn()
{
    $dsn = "mysql:host=localhost;dbname=phpclassspring2018";
    $username = "PHPClassSpring2018";
    $password = "SE266";
    try {
        $db = new PDO($dsn, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
    catch (PDOException $e)
    {
        die("The was problem connecting to the db.");
    }
}

