<?php
require_once "../connection/Database.php";

$db = new Database();
$data = $db->connect();
$data->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Clear existing attendance data
    $data->exec("DELETE FROM attendance WHERE id > 0");
    
    $startDate = new DateTime('2026-05-01');
    $endDate = new DateTime('2026-05-31');
    
    $stmt = $data->prepare(
        "INSERT INTO attendance (student_id, attendance_date, attendance_hour, status, created_at)
         VALUES (?, ?, ?, ?, ?)"
    );
    
    $totalInserted = 0;
    $current = clone $startDate;
    
    while ($current <= $endDate) {
        $attendanceDate = $current->format('Y-m-d');
        
        // For each of the 10 students
        for ($studentId = 1; $studentId <= 10; $studentId++) {
            // For each working hour (8-18, excluding lunch 13-14)
            for ($hour = 8; $hour <= 18; $hour++) {
                if ($hour === 13 || $hour === 14) {
                    continue; // Skip lunch hours
                }
                
                // Randomly assign present (75%) or absent (25%)
                $status = rand(1, 100) <= 25 ? 'absent' : 'present';
                $createdAt = $attendanceDate . ' ' . sprintf('%02d:00:00', $hour);
                
                try {
                    $stmt->execute([
                        $studentId,
                        $attendanceDate,
                        $hour,
                        $status,
                        $createdAt
                    ]);
                    $totalInserted++;
                } catch (Exception $e) {
                    // Skip duplicate entries
                }
            }
        }
        
        $current->modify('+1 day');
    }
    
    echo "✓ Test data inserted successfully!<br>";
    echo "Total attendance records: " . $totalInserted . "<br>";
    echo "Students: 10<br>";
    echo "Days: 31 (May 1-31, 2026)<br>";
    echo "Hours per day: 9 (8-12, 15-18)<br>";
    echo "<br>";
    echo "<a href='student_space.php'>Go to Student Space →</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
