<?php
function getRows()
{
    // Select all rows as associative array and return
    global $db;
    $stmt = $db->prepare("SELECT * FROM Demo");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}