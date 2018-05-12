<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
<div class="container">
    <?php
    include 'header.php';
    ?>
    <div class="row">
        <form id="listForm" action="./list.php" method="get">
            <select name="site" id="site">
                <?php
                $pdo = dbconn();
                $sql = 'SELECT site FROM sites';
                foreach ($pdo->query($sql) as $row)
                {
                    $site = $row['site'];
                    echo "<option value=\"".$site."\">".$site."</option>";
                }
                ?>
            </select>
            <button type="submit" name="search">Submit</button>
        </form>
        <table>
        <?php
        if (isset($_GET['search']))
        {
            include 'includes.php';
            $data = (getSite($_GET['site']))['site_id'];
            $pdo = dbconn();
            $sql = "SELECT link FROM sitelinks WHERE site_id = '$data'";
            foreach ($pdo->query($sql) as $row)
            {
                echo '<tr>';
                echo '<td><a href="'. $row['link'] . '">'.$row['link'].'</a></td></tr>';
            }
        }
        ?>
    </div>
</div>
</body>
</html>
