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
            <tbody>
            <?php
            include 'database.php';
            $pdo = dbconn();
            $sql = 'SELECT corp, id FROM corps';
            foreach ($pdo->query($sql) as $row) {
                echo '<tr>';
                echo '<td>'. $row['corp'] . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
