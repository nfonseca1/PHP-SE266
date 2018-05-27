<?php

session_start();
$_SESSION['isLoggedIn'] = false;

include("../includes/database.php");
include("../includes/handler.php");
include("../includes/bootstrap.html");

//Login - get inputs and see if they match the database info
if (isset($_POST['loginButton']))
{
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $pdo = dbConn();
    $sql = 'SELECT email, password FROM admins WHERE email = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_OBJ);

    if(password_verify($password, $admin->password))
    {
        echo $admin->email;
        $_SESSION['isLoggedIn'] = true;
        header('Location: index.php');
    }
    else {
        echo "Username or Password invalid";
    }
}

//Register - Get inputs and put them in vars...
if (isset($_POST['registerButton']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(ValidateRegister($email, $email2, $password, $password2))
    {
        //check if account with email exists...
        $pdo = dbconn();
        $sql = 'SELECT email, password FROM admins WHERE email = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if($admin = $stmt->fetch(PDO::FETCH_OBJ))
        {
            echo "An account is already associated with this email";
        }
        //If not, hash it and insert it into database
        else {
            $password = password_hash($password, PASSWORD_DEFAULT);

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
}
?>
<div class="container">
    <h1>Admin Login</h1>
    <br/>
    <div>
        <form action="register.php" method="POST">
            <h2>Login to your account</h2>
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input class="form-control" id="loginEmail" name="loginEmail" type="text" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input class="form-control" id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
            </div>

            <button class="btn btn-primary" type="submit" name="loginButton">LOG IN</button>

        </form>



        <form id="registerForm" action="register.php" method="POST">
            <h2>Create your free account</h2>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" required>
            </div>

            <div class="form-group">
                <label for="email2">Confirm email</label>
                <input class="form-control" id="email2" name="email2" type="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required>
            </div>

            <div class="form-group">
                <label for="password2">Confirm password</label>
                <input class="form-control" id="password2" name="password2" type="password" required>
            </div>

            <button class="btn btn-primary" type="submit" name="registerButton">SIGN UP</button>

        </form>


    </div>
</div>

</body>
</html>