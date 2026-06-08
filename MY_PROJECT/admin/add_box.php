<?php
require_once "../config/config.php" ;

$message = "";

if (isset($_POST['button_box']))
{
    $box->create($_POST['name']);
    $message = "<p id='success-message'>Box added!</p>";
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Box</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/CSS/add_box.css">
</head>

<body>
    
<div id="container">

    <?php echo $message; ?>

    <form method="POST" id="box-form">
        <h2 id="title">Add New Box</h2>

        <input 
            id="box-name" 
            name="name" 
            placeholder="Enter box name" 
            required
        >

        <button 
            type="submit" 
            name="button_box" 
            id="add-button"
        >
            Add Box
        </button>
    </form>


    <a href="../public/dashboard.php" id="return-link">
        Return to Dashboard
    </a>


</div>

</body>
</html>