<?php
require_once "../config/config.php";

if (isset($_GET['delete_box']))
{
    $box->delete($_GET['delete_box']);

    header("Location: delete_space.php");
    exit;
}


if (isset($_GET['delete_class']))
{
    $schoolclass->delete($_GET['delete_class']);

    header("Location: delete_space.php?box_id=" . $_GET['box_id']);
    exit;
}


if (isset($_GET['delete_student']))
{
    $student->delete($_GET['delete_student']);

    header("Location: delete_space.php?class_id=" . $_GET['class_id']);
    exit;
}


$box_id = $_GET['box_id'] ?? null;
$class_id = $_GET['class_id'] ?? null;
?>

<a href="../public/dashboard.php">
    <button type="button">
        Return to main page 
    </button>
</a>

<?php if (!$box_id): ?>

    <h2>Boxes</h2>

    <?php $boxes = $box->getAll(); ?>

    <?php foreach ($boxes as $b): ?>

        <div style="margin:10px;">

            <!-- OPEN BOX -->
            <a href="?box_id=<?php echo $b['id']; ?>">
                <button>
                    <?php echo $b['box_name']; ?>
                </button>
            </a>

            <!-- DELETE BOX -->
            <a href="?delete_box=<?php echo $b['id']; ?>"
               onclick="return confirm('Delete this BOX and everything inside?')">
                <button style="color:red;">
                    Delete
                </button>
            </a>

            <!-- UPDATE BOX -->
            <a href="update.php?type=box&id=<?php echo $b['id']; ?>">
                <button type="button">
                    Update
                </button>
            </a>


        </div>

    <?php endforeach; ?>

<?php endif; ?>



<?php if ($box_id && !$class_id): ?>

    <h2>Classes</h2>

    <a href="delete_space.php">
        <button>
            Return to Boxes
        </button>
    </a>


    <?php $classes = $schoolclass->get_By_id_Box($box_id); ?>

    <?php foreach ($classes as $c): ?>

        <div style="margin:10px;">

            <!-- OPEN CLASS -->
            <a href="?box_id=<?php echo $box_id; ?>&class_id=<?php echo $c['id']; ?>">
                <button>
                    <?php echo $c['class_name']; ?>
                </button>
            </a>

            <!-- DELETE CLASS -->
            <a href="?delete_class=<?php echo $c['id']; ?>&box_id=<?php echo $box_id; ?>"
               onclick="return confirm('Delete this CLASS and all students inside?')">
                <button style="color:red;">
                    Delete
                </button>
            </a>

            <!-- UPDATE CLASS -->
            <a href="update.php?type=class&id=<?php echo $c['id']; ?>&box_id=<?php echo $box_id; ?>">
                <button>
                    Update
                </button>
            </a>




        </div>

    <?php endforeach; ?>

<?php endif; ?>



<?php if ($class_id): ?>

    <h2>Students</h2>
    <a href="?box_id=<?php echo $box_id; ?>">
        <button>
            Return to Classes
        </button>
    </a>

    <?php $students = $student->getByClass($class_id); ?>

    <table border="1">

        <?php foreach ($students as $s): ?>

            <tr>

                <td>
                    <img src="../upload/<?php echo $s['photo']; ?>" width="50">
                </td>

                <td><?php echo $s['name']; ?></td>

                <td><?php echo $s['class_name']; ?></td>

                <td><?php echo $s['box_name']; ?></td>

                <td>
                    <a href="?delete_student=<?php echo $s['id']; ?>&class_id=<?php echo $class_id; ?>"
                       onclick="return confirm('Delete this student?')">
                        <button style="color:red;">
                            Delete
                        </button>
                    </a>

                   <a href="update.php?type=student&id=<?php echo $s['id']; ?>&box_id=<?php echo $box_id; ?>&class_id=<?php echo $class_id; ?>">
                        <button>
                            Update
                        </button>
                   </a>
                </td>

                

            </tr>

        <?php endforeach; ?>

    </table>

<?php endif; ?>