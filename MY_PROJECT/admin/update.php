<?php
require_once "../config/config.php";

$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

$box_id = $_GET['box_id'] ?? null;
$class_id = $_GET['class_id'] ?? null;


/* =========================
   UPDATE BOX
========================= */

if ($type == "box")
{
    $stmt = $data->prepare("
        SELECT * FROM boxes
        WHERE id = :id
    ");

    $stmt->execute([
        ":id" => $id
    ]);

    $box_data = $stmt->fetch(PDO::FETCH_ASSOC);



    if (isset($_POST['update_box']))
    {
        $stmt = $data->prepare("
            UPDATE boxes
            SET box_name = :name
            WHERE id = :id
        ");

        $stmt->execute([
            ":name" => $_POST['name'],
            ":id" => $id
        ]);

        echo "Box updated!";
    }
    ?>

    <!-- ADDED WRAPPER -->
    <div class="update-container">

    <form method="POST">

        <input
            type="text"
            name="name"
            value="<?php echo $box_data['box_name']; ?>"
        >

        <button name="update_box">
            Update Box
        </button>

        <a href="delete_space.php">
            <button type="button">
                Return to Boxes
            </button>
        </a>

    </form>

    </div>

    <?php
}



/* =========================
   UPDATE CLASS
========================= */

if ($type == "class")
{
    $stmt = $data->prepare("
        SELECT * FROM classes
        WHERE id = :id
    ");

    $stmt->execute([
        ":id" => $id
    ]);

    $class_data = $stmt->fetch(PDO::FETCH_ASSOC);



    if (isset($_POST['update_class']))
    {
        $stmt = $data->prepare("
            UPDATE classes
            SET class_name = :name
            WHERE id = :id
        ");

        $stmt->execute([
            ":name" => $_POST['name'],
            ":id" => $id
        ]);

        echo "Class updated!";
    }
    ?>

    <!-- ADDED WRAPPER -->
    <div class="update-container">

    <form method="POST">

        <input
            type="text"
            name="name"
            value="<?php echo $class_data['class_name']; ?>"
        >

        <button name="update_class">
            Update Class
        </button>

       <a href="../admin/delete_space.php?box_id=<?php echo $box_id; ?>">
            <button type="button">
                Return to Classes
            </button>
       </a>

    </form>

    </div>

    <?php
}



/* =========================
   UPDATE STUDENT
========================= */

if ($type == "student")
{
    $stmt = $data->prepare("
        SELECT * FROM students
        WHERE id = :id
    ");

    $stmt->execute([
        ":id" => $id
    ]);

    $student_data = $stmt->fetch(PDO::FETCH_ASSOC);



    if (isset($_POST['update_student']))
    {
        $photo = $student_data['photo'];

        if (!empty($_FILES['photo']['name']))
        {
            $photo = $_FILES['photo']['name'];

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                "../upload/" . $photo
            );
        }

        $stmt = $data->prepare("
            UPDATE students
            SET
                name = :name,
                photo = :photo
            WHERE id = :id
        ");

        $stmt->execute([
            ":name" => $_POST['name'],
            ":photo" => $photo,
            ":id" => $id
        ]);

        echo "Student updated!";
    }
    ?>

    <!-- ADDED WRAPPER -->
    <div class="update-container">

    <form method="POST" enctype="multipart/form-data">

        <input
            type="text"
            name="name"
            value="<?php echo $student_data['name']; ?>"
        >

        <br><br>

        <img
            src="../upload/<?php echo $student_data['photo']; ?>"
            width="100"
        >

        <br><br>

        <input type="file" name="photo">

        <br><br>

        <button name="update_student">
            Update Student
        </button>

        <a href="../admin/delete_space.php?box_id=<?php echo $box_id; ?>&class_id=<?php echo $class_id; ?>">
            <button type="button">
                Return
            </button>
        </a>

    </form>

    </div>

    <?php
}
?>

<link rel="stylesheet" href="../admin/CSS/update.css">