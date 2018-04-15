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
            include 'database.php';
            $pdo = dbconn();
            $sql = 'SELECT corp, id FROM corps';
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
