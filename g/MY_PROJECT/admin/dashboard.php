<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit;
}
echo "Welcome " . $_SESSION['username'] . "<br>";

if ($_SESSION['role'] === 'admin')
{
    echo "You are ADMIN 👑<br>";
    echo "<a href='admin_panel.php'>Go to Admin Panel</a>";
}
else
{
    echo "You are USER 👤<br>";
}

// Logout link
echo "<br><a href='../public/logout.php'>Logout</a>";
?>