<?php
require_once "../classes/User.php" ;
require_once "../classes/Box.php" ;
require_once "../classes/SchoolClass.php" ;
require_once "../classes/Student.php" ;
require_once "../classes/Attendance.php" ;

$data = (new Database())->connect() ;

function ensureAttendanceSchema($db)
{
    try 
    {
        $columns = [];
        $stmt = $db->query("SHOW COLUMNS FROM attendance");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $columns[$row['Field']] = true ;
        }

        if (!isset($columns['admin_flagged'])) 
        {
            $db->exec("ALTER TABLE attendance ADD COLUMN admin_flagged TINYINT(1) NOT NULL DEFAULT 0");
        }

        if (!isset($columns['flagged_at'])) 
        {
            $db->exec("ALTER TABLE attendance ADD COLUMN flagged_at DATETIME NULL");
        }

        if (!isset($columns['attendance_hour']))
        {
            $db->exec("ALTER TABLE attendance ADD COLUMN attendance_hour INT NOT NULL DEFAULT 0");
        }
    } 
    catch (Exception $e) 
    {
        // If schema cannot be adjusted, do not block page rendering.
    }
}

ensureAttendanceSchema($data);

$user        =    new User($data);
$box         =    new Box($data);
$schoolclass =    new SchoolClass($data);
$student     =    new Student($data);
$attendance  =    new Attendance($data);
?>