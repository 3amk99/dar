<?php
session_start();
require_once "../config/config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) 
{
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$student_id = $_POST['student_id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$student_id || !in_array($status, ['present', 'absent'])) 
{
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$attendance_hour = date("H");
$attendance_date = date("Y-m-d");
$now = date("Y-m-d H:i:s");

$stmt = $data->prepare(
    "SELECT * FROM attendance WHERE student_id = ? AND attendance_date = ? AND attendance_hour = ?"
);
$stmt->execute([
    $student_id,
    $attendance_date,
    $attendance_hour
]);

$attendance_result = $stmt->fetch(PDO::FETCH_ASSOC);
$flagged = 0;
$flagCleared = 0;

if ($attendance_result) {
    $currentFlagged = (int) ($attendance_result['admin_flagged'] ?? 0);
    $flaggedAt = $attendance_result['flagged_at'] ?? null;

    if ($status === 'absent') {
        $flagged = 1;
        $flaggedAt = $now;
    } else {
        if ($currentFlagged && $flaggedAt) {
            $flagTime = strtotime($flaggedAt);
            if ($flagTime >= strtotime('-1 hour')) {
                $flagged = 0;
                $flaggedAt = null;
                $flagCleared = 1;
            } else {
                $flagged = 1;
            }
        }
    }

    $stmt = $data->prepare(
        "UPDATE attendance SET status = ?, admin_flagged = ?, flagged_at = ? WHERE id = ?"
    );
    $stmt->execute([
        $status,
        $flagged,
        $flaggedAt,
        $attendance_result['id']
    ]);
} else {
    $flagged = $status === 'absent' ? 1 : 0;
    $flaggedAt = $flagged ? $now : null;

    $stmt = $data->prepare(
        "INSERT INTO attendance
            (student_id, status, attendance_date, attendance_hour, admin_flagged, flagged_at)
        VALUES
            (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $student_id,
        $status,
        $attendance_date,
        $attendance_hour,
        $flagged,
        $flaggedAt
    ]);
}

echo json_encode([
    'success' => true,
    'flagged' => $flagged,
    'flagCleared' => $flagCleared,
    'stillFlagged' => $flagged
]);
?>