
<?php
include 'includes/database.php';
include 'includes/header.php';
?>

            <?php
            $pdo = dbconn();
            if ( isset( $_GET['action1']) )
            {
                $col = $_GET['col'];
                $dir = $_GET['dir'];
            } else {
                $col = 'id';
                $dir = 'ASC';
            }
            if ( isset( $_GET['action2']) )
            {
                $col2 = $_GET['col2'];
                $term = $_GET['term'];
            } else {
                $col2 = 'corp';
                $term = '';
            }

            $sql = 'SELECT corp, id FROM corps WHERE ' . $col2 . ' LIKE ' . "'%" . $term . "%'" . ' ORDER BY '. $col . ' ' . $dir;
            foreach ($pdo->query($sql) as $row) {
                echo '<tr>';
                echo '<td>'. $row['corp'] . '</td>';
                echo '<td width=250>';
                echo '<a href="read.php?id='.$row['id'].'">Read</a>';
                echo ' ';
                echo '<a href="update.php?id='.$row['id'].'">Update</a>';
                echo ' ';
                echo '<a href="delete.php?id='.$row['id'].'">Delete</a>';
                echo '</td>';
                echo '</tr>';
            }
            ?>

<?php
include('includes/footer.php');
?>
