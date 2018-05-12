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
        <form id="siteForm" action="./index.php" method="post">
            <input type="text" name="link" id="link" placeholder="Site Link">
            <button type="submit" name="insert">Submit</button>
        </form>
            <?php

            if ( isset( $_POST['insert']) )
            {
                $site = $_POST['link'];

                if (filter_var($site, FILTER_VALIDATE_URL) === FALSE) {
                    die('Not a valid URL');
                }
                else
                {
                    include 'includes.php';
                    siteInsert($site);

                    $data=file_get_contents($site);
                    $data = strip_tags($data,"<a>");
                    $d = preg_split("/<\/a>/",$data);
                    foreach ( $d as $k=>$u ){
                        if( strpos($u, "<a href=") !== FALSE ){
                            $u = preg_replace("/.*<a\s+href=\"/sm","",$u);
                            $u = preg_replace("/\".*/","",$u);
                            print $u;
                            $data = getSite($site);
                            linkInsert($data, $u);
                            echo '<br>';
                        }
                    }
                }
            }
            else if ( isset($_POST['delete']) )
            {
                echo "Delete";
            }
            ?>
    </div>
</div>
</body>
</html>
