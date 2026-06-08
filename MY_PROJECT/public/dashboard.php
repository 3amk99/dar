<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <!-- ADDED FOR RESPONSIVE DESIGN -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../admin/CSS/dashboard.css">

    <title>Dashboard</title>
</head>

<body>

<div class="dashboard-container">

    <?php
        echo "<h2>Welcome " . htmlspecialchars($_SESSION['username']) . "</h2>";
        echo "<p>Role: " . htmlspecialchars($_SESSION['role']) . "</p>";

        if ($_SESSION['role'] === 'admin')
        {
            echo "<h3>You are ADMIN 👑</h3>";


            echo "<div class='link-box'>";
            echo "<a href='../admin/add_box.php'>Add year_level</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/add_class.php'>Add Class</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/add_student.php'>Add Student</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/assign_classes.php'>Assign Classes to Teachers</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/student_space.php'>Mark Attendance</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/delete_space.php'>[Delete] [Update]</a>";
            echo "</div>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/check_page.php'>Check Students</a>";
            echo "</div>";
        }
        else
        {
            echo "<h3>You are TEACHER 👤</h3>";

            echo "<div class='link-box'>";
            echo "<a href='../admin/student_space.php'>Mark Attendance</a>";
            echo "</div>";
        }

        // Logout
        echo "<div class='logout-box'>";
        echo "<a href='logout.php'>Logout</a>";
        echo "</div>";
    ?>

</div>

</body>
</html>