<?php
session_start();
require_once "../config/config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
{
    header("Location: ../public/login.php");
    exit;
}

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear_flag') 
{
    $student_id = intval($_POST['student_id'] ?? 0);
    if ($student_id > 0) 
    {
        $stmt = $data->prepare(
            "UPDATE attendance 
            SET admin_flagged = 0,
            flagged_at = NULL 
            WHERE 
            student_id = :student_id AND
            admin_flagged = 1"
        );
        $stmt->execute([':student_id' => $student_id]);
        $response = ['success' => true];
    } 
    else 
    {
        $response = ['success' => false, 'message' => 'Invalid student id'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$stmt = $data->prepare(
    "SELECT DISTINCT 
     students.id,
     students.name,
     students.photo,
     classes.class_name AS class_name,
     boxes.box_name AS box_name

     FROM students
     JOIN classes ON students.class_id = classes.id
     JOIN boxes ON classes.box_id = boxes.id
     JOIN attendance ON attendance.student_id = students.id

     WHERE attendance.admin_flagged = 1
     ORDER BY students.name"
);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Absence Review</title>

    <link rel="stylesheet" href="../admin/CSS/check_page.css">
</head>

<body>

    <h2>Admin Absence Review</h2>

    <p>Only students with an active absence flag are shown.</p>

    <?php if (empty($students)): ?>

        <p>No flagged students at the moment.</p>

    <?php else: ?>

        <?php foreach ($students as $s): ?>

            <div class="student-bar">

                <img
                    src="../upload/<?= htmlspecialchars($s['photo']) ?>"
                    alt="<?= htmlspecialchars($s['name']) ?>"
                >

                <div class="student-info">

                    <h3><?= htmlspecialchars($s['name']) ?></h3>

                    <p>
                        Class:
                        <?= htmlspecialchars($s['class_name']) ?>
                    </p>

                    <p>
                        Box:
                        <?= htmlspecialchars($s['box_name']) ?>
                    </p>

                    <a href="student_stats.php?id=<?= $s['id'] ?>">
                        <button type="button">
                            Statistics
                        </button>
                    </a>

                    <button
                        class="clear-flag-btn"
                        onclick="clearFlag(<?= $s['id'] ?>, this)"
                    >
                        Remove Flag
                    </button>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

    <a href="student_space.php">
        <button type="button">
            Back to Student Space
        </button>
    </a>

    <script>
    function clearFlag(studentId, button)
    {
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type':
                'application/x-www-form-urlencoded'
            },
            body:
            'action=clear_flag&student_id=' +
            studentId
        })
        .then(response => response.json())
        .then(data =>
        {
            if (!data.success)
            {
                alert('Unable to clear flag.');
                return;
            }

            const row =
            button.closest('.student-bar');

            if (row)
            {
                row.remove();
            }

            if (!document.querySelector('.student-bar'))
            {
                const msg =
                document.createElement('p');

                msg.textContent =
                'No flagged students at the moment.';

                document.body.insertBefore(
                    msg,
                    document.querySelector('a')
                );
            }
        });
    }
    </script>

</body>
</html>
