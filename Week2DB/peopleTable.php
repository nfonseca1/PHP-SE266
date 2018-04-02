<?php
foreach ($people as $person)
{
    echo $person['fName'];
    echo $person['lName'];
    echo $person['age'];
    echo "<br />";
}
?>

<form action='index.php' method='get'>
    <input type='submit' name='action' value='Add' />
</form>
