<?php

session_start();
$_SESSION['isLoggedIn'] = false;

include("../includes/database.php");

if (isset($_POST['loginButton']))
{
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $pdo = dbConn();
    $sql = 'SELECT email, password FROM admins WHERE email = ? && password = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $password]);

    if($admin = $stmt->fetch(PDO::FETCH_OBJ))
    {
        echo $admin->email;
        $_SESSION['isLoggedIn'] = true;
        header('Location: index.php');
    }
    else {
        echo "Username or Password invalid";
    }
}

if (isset($_POST['registerButton']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    //check if account with email exists
    $pdo = dbconn();
    $sql = 'SELECT email, password FROM admins WHERE email = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    if($admin = $stmt->fetch(PDO::FETCH_OBJ))
    {
        echo "An account is already associated with this email";
    }
    else {
        $sql = "INSERT INTO admins (email, password) values(?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute(array($email, $password)))
        {
            $_SESSION['isLoggedIn'] = true;
            header('Location: index.php');
        }
        else{
            echo "An Error Has Occurred";
        }
    }
}
?>

<div id="inputContainer">
    <form id="loginForm" action="register.php" method="POST">
        <h2>Login to your account</h2>
        <p>
            <label for="loginEmail">Email</label>
            <input id="loginEmail" name="loginEmail" type="text" placeholder="Username" required>
        </p>
        <p>
            <label for="loginPassword">Password</label>
            <input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
        </p>

        <button type="submit" name="loginButton">LOG IN</button>

    </form>



    <form id="registerForm" action="register.php" method="POST">
        <h2>Create your free account</h2>

        <p>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>
        </p>

        <p>
            <label for="email2">Confirm email</label>
            <input id="email2" name="email2" type="email" required>
        </p>

        <p>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </p>

        <p>
            <label for="password2">Confirm password</label>
            <input id="password2" name="password2" type="password" required>
        </p>

        <button type="submit" name="registerButton">SIGN UP</button>

    </form>


</div>

</body>
</html>