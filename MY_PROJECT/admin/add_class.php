<?php
require_once "../config/config.php";

$boxes_result = $box->getAll();

$message = "";

if (isset($_POST['button_schoolclass']))
{
    $schoolclass->create($_POST['name'], $_POST['box_id']);

    $message = "<p id='success-message'>Class added!</p>";
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../admin/CSS/add_class.css">

<div id="container">

    <?php echo $message; ?>

    <form method="POST" id="form-box">

        <h2 id="title">Add New Class</h2>

        <input 
            id="input-name" 
            name="name" 
            placeholder="Class name" 
            required
        >

        <select name="box_id" id="select-box">

            <?php foreach ($boxes_result as $b) { ?>
                <option value="<?php echo $b['id']; ?>">
                    <?php echo $b['box_name']; ?>
                </option>
            <?php } ?>

        </select>

        <button type="submit" id="btn-add" name="button_schoolclass">
            Add Class
        </button>

    </form>

    <a href="../public/dashboard.php" id="btn-return">
        Return
    </a>

</div>

</body>
</html>