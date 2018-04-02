<?php
require_once("db.php");
require_once("people.php");
$action = $_REQUEST['action'];

switch($action)
{
    default:
        $people = getRows();
        include_once("peopleTable.php");
}