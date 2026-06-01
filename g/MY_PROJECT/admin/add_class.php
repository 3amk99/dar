<?php
require_once "../config/config.php" ;
$boxes_result = $box->getAll();
if (isset($_POST['button_schoolclass']))
{
    $schoolclass->create($_POST['name'], $_POST['box_id']);
    echo "Class added!";
}
?>

<form method="POST">
    <input name="name" placeholder="Class name">

    <select name="box_id">

        <?php foreach ($boxes_result as $b) { ?>

            <option value="<?php echo $b['id']; ?>">

                <?php echo $b['box_name']; ?>

            </option>

        <?php } ?>

    </select>

    <button name="button_schoolclass">Add Class</button>
</form>

 <button>
     <a href='../public/dashboard.php'>return </a>
 </button>