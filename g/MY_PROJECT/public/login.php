<?php
session_start();
require_once "../config/config.php" ;

if (isset($_POST['login_page']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password))
    {
        header("Location: dashboard.php");
        exit;
    }
    else
    {
        echo "Invalid login!";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit" name="login_page">Login</button>
</form>