<?php
session_start();
require_once "../config/config.php" ;

$error = "";

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
        $error = "Invalid login!";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login</title>

    <style>
        body
        {
            margin: 0;
            font-family: Arial;
            background: #f4f4f4;

            display: flex;
            justify-content: center;
            align-items: center;

            min-height: 100vh;
        }

        form
        {
            width: 100%;
            max-width: 350px;

            background: white;
            padding: 20px;

            border: 2px solid #0b3d2e;
            border-radius: 4px;

            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input
        {
            padding: 12px;
            border: 1px solid #0b3d2e;
            border-radius: 4px;
            outline: none;
        }

        input:focus
        {
            border: 2px solid #145a3a;
        }

        button
        {
            padding: 10px 15px;

            background: #0b3d2e;
            color: white;

            border: none;
            border-radius: 4px;

            cursor: pointer;
            transition: 0.3s;
        }

        button:hover
        {
            background: #145a3a;
        }

        .error
        {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>

</head>

<body>

<form method="POST">

    <?php if ($error) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login_page">
        Login
    </button>

</form>

</body>
</html>