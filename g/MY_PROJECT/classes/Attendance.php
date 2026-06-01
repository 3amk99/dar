<?php
require_once "../connection/Database.php";
class Attendance
{
    private $conn;
    private $table = "attendance" ;

    public $student_id ;
    public $status ;
    public $today ;

    public $attendance_date ;
    public $attendance_hour ;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function get_Today_Attendance($student_id , $attendance_date , $attendance_hour , $status )
    {
     
        $stmt = $this->conn->prepare("
            SELECT id
            FROM attendance
            WHERE student_id = ?
            AND attendance_date = ?
            AND attendance_hour = ?
        ");

        $stmt->execute([
            $student_id,
            $attendance_date,
            $attendance_hour
        ]);

        $attendance_result = $stmt->fetch();



        if ($attendance_result)
        {
            
            $stmt = $this->conn->prepare("
                UPDATE attendance
                SET status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $status,
                $attendance_result['id']
            ]);
        }
        else
        {   
            $stmt = $this->conn->prepare("
                INSERT INTO attendance
                (
                    student_id,
                    status,
                    attendance_date,
                    attendance_hour
                )

                VALUES
                (
                    ?, ?, ?, ?
                )
            ");

            $stmt->execute([
                $student_id ,
                $status ,
                $attendance_date ,
                $attendance_hour
            ]);
        }

        echo "success";
    }

}