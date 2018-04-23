<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>

<body>
<div class="container">
    <div class="row">
        <h3>Corporations</h3>
    </div>
    <?php
    include 'header.php';
    ?>
    <div class="row">
        <p>
            <a href="create.php" class="btn btn-success">Create</a>
        </p>

        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
