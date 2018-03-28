<?php
$size = mt_rand(5, 10);
$table = "<table>\n";
for ($rows = 1; $rows <= $size; $rows++)
{
    $table .= "\t<tr>";
    $table .= "</tr>\n";
}
$table .= "</table>\n";
echo $table;
?>
