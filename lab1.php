<?php
$hexDig = "";

$table = "<table>";
for ($rows=1; $rows <= 10; $rows++){
    $table .= "\t<tr>";
    for ($cols=1; $cols<=10; $cols++){
        $table .= "<td ";
        for ($hex=0; $hex <6; $hex++){
            $hexDig .= dechex(mt_rand(1, 16));
        }
        $table .= "bgcolor=\"";
        $table .= (string)$hexDig;
        $table .= "\" >";
        $table .= "<pre>";
        $table .= (string)$hexDig;
        $table .= "\n<span style=\"color:white\">";
        $table .= (string)$hexDig;
        $table .= "</span>";
        $table .= "</pre>";
        $table .= "</td>";

        $hexDig = "";
    }
    $table .= "</tr>\n";
}
$table .= "</table>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php echo $table; ?>
</body>
</html>