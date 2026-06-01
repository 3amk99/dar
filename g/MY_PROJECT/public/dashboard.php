<?php
session_start();

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit;
}

echo "Welcome " . htmlspecialchars($_SESSION['username']) . "<br>";
echo "Role: " . htmlspecialchars($_SESSION['role']) . "<br>";

if ($_SESSION['role'] === 'admin')
{
    echo "You are ADMIN 👑<br>";
    echo "<a href='admin_panel.php'>Go to Admin Panel</a><br>";
    echo "<a href='../admin/add_box.php'>Add Box</a><br>";
    echo "<a href='../admin/add_class.php'>Add Class</a><br>";
    echo "<a href='../admin/add_student.php'>Add Student</a><br>";
    echo "<a href='../admin/assign_classes.php'>Assign Classes to Teachers</a><br>";
    echo "<a href='../admin/student_space.php'>Mark Attendance / Student Space</a><br>";
    echo "<a href='../admin/delete_space.php'>Delete / Update Space</a><br>";
}
else
{
    echo "You are USER 👤<br>";
    echo "<a href='../admin/student_space.php'>Mark Attendance</a><br>";
}

// Logout link
echo "<br><a href='logout.php'>Logout</a><br>";
?>
