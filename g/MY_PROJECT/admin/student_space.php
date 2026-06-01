<?php
session_start();
require_once "../config/config.php";

if (!isset($_SESSION['user_id'])) 
{
    header("Location: ../public/login.php");
    exit;
}

$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

$boxes = $box->getAll();
$selectedClassId = $_GET['class_id'] ?? null;
$selectedBoxId = $_GET['box_id'] ?? null;
$classes = [];
$accessError = null;

if ($user_role === 'admin') 
{
    if ($selectedBoxId) 
    {
        $classes = $schoolclass->get_By_id_Box($selectedBoxId);
    }
} 
else 
{
    $stmt = $data->prepare(
        "SELECT c.id, c.class_name FROM classes c
         JOIN user_class uc ON c.id = uc.class_id
         WHERE uc.user_id = :uid"
    );
    $stmt->execute([':uid' => $user_id]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$students = [];
if ($selectedClassId) 
{
    if ($user_role !== 'admin') 
    {
        $stmt = $data->prepare(
            "SELECT 1 FROM user_class WHERE user_id = :uid AND class_id = :cid LIMIT 1"
        );
        $stmt->execute([':uid' => $user_id, ':cid' => $selectedClassId]);
        if (!$stmt->fetch()) 
        {
            $accessError = "You do not have access to this class.";
            $selectedClassId = null;
        }
    }

    if ($selectedClassId) 
    {
        $stmt = $data->prepare(
            "SELECT
                students.*, classes.class_name AS class_name, boxes.box_name AS box_name,
                EXISTS(
                    SELECT 1 FROM attendance
                    WHERE attendance.student_id = students.id
                      AND attendance.admin_flagged = 1
                ) AS has_flag
            FROM students
            JOIN classes ON students.class_id = classes.id
            JOIN boxes ON classes.box_id = boxes.id
            WHERE students.class_id = :class_id"
        );
        $stmt->execute([":class_id" => $selectedClassId]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Space</title>
</head>
<body>
    <h2>Student Attendance</h2>

    <?php if ($user_role === 'admin'): ?>
        <p><a href="check_page.php" style="text-decoration:none;"><button type="button">Admin Absence Check</button></a></p>
        <p>You are admin. Choose a box to select a class.</p>

        
        <form method="GET" style="margin-bottom:1rem;">
            <select name="box_id">
                <option value="">-- choose box --</option>
                <?php foreach ($boxes as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= ($selectedBoxId == $b['id']) ? 'selected' : '' ?>><?= htmlspecialchars($b['box_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Select Box</button>
        </form>



    <?php else: ?>
        <p>You are teacher. Choose a class to mark attendance.</p>
    <?php endif; ?>



    <?php if (!empty($accessError)): ?>
        <p style="color:red;"><?= htmlspecialchars($accessError) ?></p>
    <?php endif; ?>




    <?php if (!empty($classes)): ?>
        <div style="margin-bottom:20px;">
            <?php foreach ($classes as $class): ?>
                <a href="?class_id=<?= $class['id'] ?>" style="display:inline-block;margin:4px;padding:8px;border:1px solid #333;text-decoration:none;color:#000;">
                    <?= htmlspecialchars($class['class_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>



<!-- ///ida ma3anch lcla11 '3and lu1er -->
    <?php elseif ($user_role !== 'admin'): ?>
        <p>No classes assigned. Ask the admin to assign your classes.</p>
    <?php endif; ?>





    <?php if (!empty($students)): ?>

        <?php foreach ($students as $s): ?>
            <div class="student-bar" style="margin-bottom:18px;padding:12px;border:1px solid #ddd;">
                <img src="../upload/<?= htmlspecialchars($s['photo']) ?>" width="80" alt="<?= htmlspecialchars($s['name']) ?>">
                <h3><?= htmlspecialchars($s['name']) ?></h3>
                <p>Class: <?= htmlspecialchars($s['class_name']) ?></p>
                <p>Box: <?= htmlspecialchars($s['box_name']) ?></p>
                <button class="present-btn" onclick="saveAttendance(<?= $s['id'] ?>, 'present', this)">Present</button>
                <button class="absent-btn" onclick="saveAttendance(<?= $s['id'] ?>, 'absent', this)">Absent</button>


                <?php if (!empty($s['has_flag'])): ?>
                    <button class="flag-btn" disabled>Flagged</button>
                <?php endif; ?>


                <a href="student_stats.php?id=<?= $s['id'] ?>"><button>Statistics</button></a>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <a href="../public/dashboard.php"><button type="button">Return to main page</button></a>

    <style>
        .flag-btn {
            background: orange;
            color: #000;
            border: none;
            padding: 8px 12px;
            margin-left: 8px;
            cursor: default;
        }
    </style>

    <script>
    function saveAttendance(studentId, status, button) 
    {
        fetch("save_attendance.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "student_id=" + studentId + "&status=" + status
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) 
            {
                return;
            }

            let parent = button.parentElement;
            parent.style.background = status === 'present' ? 'lightgreen' : 'lightcoral';

            let flagBtn = parent.querySelector('.flag-btn');
            if (data.stillFlagged) {
                if (!flagBtn) {
                    flagBtn = document.createElement('button');
                    flagBtn.className = 'flag-btn';
                    flagBtn.disabled = true;
                    flagBtn.textContent = 'Flagged';
                    button.insertAdjacentElement('afterend', flagBtn);
                }
            } else {
                if (flagBtn) {
                    flagBtn.remove();
                }
            }
        });
    }
    </script>
</body>
</html>