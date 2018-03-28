<?php
$size = mt_rand(5, 10);
$table = "<table>\n";
for ($rows = 1; $rows <= $size; $rows++)
{
    $table .= "\t<tr>";
    for ($cols = 1; $cols<=$size; $cols++)
    {
        $table.="<td>" . $rows * $cols . "</td>";
    }
    $table .= "</tr>\n";
}
$table .= "</table>\n";
echo $table;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multiplication Table</title>
</head>
<body>
<?php echo $table; ?>
</body>
</html>