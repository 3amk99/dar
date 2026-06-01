
















<?php
require_once "../config/config.php";

$student_id = $_GET['student_id'];
$type = $_GET['type'];
$date = isset($_GET['date']) ? $_GET['date'] : null;

// if ($type == "day")
// {
//         $stmt = $data->prepare("
//             SELECT
//                 attendance_hour as hour,
//                 status
//             FROM attendance
//             WHERE student_id = ?
//             AND attendance_date = ?
//             AND attendance_hour BETWEEN 8 AND 18
//             ORDER BY attendance_hour
//     ");

//     $stmt->execute([$student_id , $date]);

//     $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     $result = [] ;

//     foreach ($rows as $row)
//     {

//         $present = 0 ;
//         $absent = 0 ;

//         if ($row['status'] == "present")
//         {
//             $present = 1 ;
//         }
//         else
//         {
//             $absent = 1 ;
//         }

//         $result[] =
//         [
//             "hour" => $row['hour'] ,
//             "present" => $present ,
//             "absent" => $absent
//         ];
//     }

//     echo json_encode($result);

//     exit;
// }

if ($type == "day") 
{
    $stmt = $data->prepare("
        SELECT 
            attendance_hour as hour,
            status
        FROM attendance
        WHERE student_id = ?
        AND attendance_date = ?
        AND attendance_hour BETWEEN 8 AND 18
    ");

    $stmt->execute([$student_id, $date]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);
    exit;
}


// GROUP BY week  -> order weeks numbers with the the status 'absent' like 
// 1   absent
// 1   absent
// 1   absent

// ORDER BY week -> just the oder of weeks numbers , it like organize them in other from the small to the bigest 
if ($type == "week") 
{

    $stmt = $data->prepare("
       SELECT 
       WEEK(created_at) as week,
       COUNT(*) as total

            FROM attendance
            WHERE student_id = ?
            AND status = 'absent'
            GROUP BY week 
            ORDER BY week
    ");

    $stmt->execute([$student_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($rows as $row) 
    {
        $w = $row['week'];

        $result[$w] = 
        [
            "week" => $w,
            "absent" => (int)$row['total']
        ];
    }

    echo json_encode(array_values($result));
    exit;
}


if ($type == "month") 
{

    $stmt = $data->prepare("
        SELECT 
            MONTH(created_at) as month,
            COUNT(*) as total
        FROM attendance
        WHERE student_id = ?
        AND status = 'absent'
        GROUP BY month
        ORDER BY month
    ");

    $stmt->execute([$student_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($rows as $row) 
    {
        $m = $row['month'];

        $result[] = [
            "month" => $m,
            "absent" => (int)$row['total']
        ];
    }

    echo json_encode($result);
    exit;
}