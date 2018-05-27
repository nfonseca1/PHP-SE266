<?php

session_start();
$_SESSION['userLoggedIn'] = false;

include("includes/database.php");
include("includes/handler.php");
include("includes/header.php");
//Login - get inputs and see if they match the database info
if (isset($_POST['loginButton']))
{
    $email = $_POST['loginEmail'];
    $_SESSION['email'] = $email;
    $password = $_POST['loginPassword'];

    $pdo = dbConn();
    $sql = 'SELECT email, password FROM customers WHERE email = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $customer = $stmt->fetch();

    if(password_verify($password, $customer['password']))
    {
        $_SESSION['userLoggedIn'] = true;
        header('Location: ' . $_SESSION['redirect']);
    }
    else {
        echo "Username or Password invalid";
    }
}

//Register - Get inputs and put them in vars...
if (isset($_POST['registerButton']))
{
    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    $email2 = $_POST['email2'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    //Perform validation process for each input
    if(ValidateRegister($email, $email2, $password, $password2))
    {
        //check if account with email exists...
        $pdo = dbconn();
        $sql = 'SELECT email, password FROM customers WHERE email = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        if($customer = $stmt->fetch(PDO::FETCH_OBJ))
        {
            echo "An account is already associated with this email";
        }
        //If not, hash it and insert it into database
        else {
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers (email, password, created) values(?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute(array($email, $password, date('Y-m-d H:i:s'))))
            {
                $_SESSION['userLoggedIn'] = true;
                header('Location: ' . $_SESSION['redirect']);
            }
            else{
                echo "An Error Has Occurred";
            }
        }
    }
}
?>

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

</body>
</html>