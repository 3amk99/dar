<?php
require_once "../config/config.php" ;
$classes_result = $schoolclass->getAll();
if (isset($_POST['button_student']))
{
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "../upload/".$photo);

    $student->create($_POST['name'], $photo, $_POST['schoolclass_id']);

    echo "Student added!";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input name="name" placeholder="Student name">

    <input type="file" name="photo">

    <select name="schoolclass_id">
        <?php foreach ($classes_result as $b) { ?>

            <option value="<?php echo $b['id']; ?>">

                <?php echo $b['class_name']; ?>

            </option>

        <?php } ?>
    </select>

    <button name="button_student">Add Student</button>
</form>
 <button>
     <a href='../public/dashboard.php'>return </a>
 </button>