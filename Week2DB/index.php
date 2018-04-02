<?php
require_once("db.php");
require_once("people.php");
$action = $_REQUEST['action'];
$fName = $_POST['fName'];
$lName = $_POST['lName'];
$age = $_POST['age'];

switch($action)
{
    case "Add":
        include_once("personForm.php");
        break;
    case "Save":
        savePerson($db, $fName, $lName, $age);
        $people = getRows();
        include_once("peopleTable.php");
        break;
    default:
        $people = getRows();
        include_once("peopleTable.php");
}