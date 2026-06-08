<?php
require_once "../config/config.php" ;


$classes_result = $schoolclass->getAll();


if (isset($_POST['button_student']))
{
    $photo = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "../upload/".$photo);

    $student->create($_POST['name'], $photo, $_POST['schoolclass_id']);


    echo "<p id='success-message'>Student added!</p>";
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../admin/CSS/add_student.css">

<div id="page">

<form method="POST" enctype="multipart/form-data" id="student-form">

    <input id="input-name" name="name" placeholder="Student name">

    
    <label for="input-photo" class="custom-file-btn">
        Choose Photo
    </label>
    <input id="input-photo" type="file" name="photo">


    <select id="select-class" name="schoolclass_id">

        <?php foreach ($classes_result as $b) { ?>

            <option value="<?php echo $b['id']; ?>">
                <?php echo $b['class_name']; ?>
            </option>

        <?php } ?>

    </select>

    <button id="btn-add" name="button_student">
        Add Student
    </button>

</form>

<a href="../public/dashboard.php" id="btn-return">
    Return
</a>

</div>