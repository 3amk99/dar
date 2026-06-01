<?php
require_once "../config/config.php" ;

if (isset($_POST['button_box']))
{
    $box->create($_POST['name']);
    echo "Box added!";
}
?>

<form method="POST">
    <input name="name" placeholder="Box name">
    <button name="button_box">Add Box</button>
</form>

 <button>
     <a href='../public/dashboard.php'>return </a>
 </button>