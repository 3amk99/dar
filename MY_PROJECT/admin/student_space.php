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
else // teacher
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
    if ($user_role !== 'admin') // teachers can only access their assigned classes
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

    if ($selectedClassId) // load students if no access error
    {
        $stmt = $data->prepare(
            "SELECT
                students.*,
                classes.class_name AS class_name,
                boxes.box_name     AS box_name,
                EXISTS
                (
                    SELECT 1 FROM attendance
                    WHERE attendance.student_id = students.id
                    AND attendance.admin_flagged = 1
                )                  AS has_flag 
            FROM students

            JOIN classes ON students.class_id = classes.id
            JOIN boxes   ON classes.box_id = boxes.id

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../admin/CSS/student_space.css">
    <!-- <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f4f4;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border: 2px solid #0b3d2e;
        }

        h2 {
            text-align: center;
            color: #0b3d2e;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-dark {
            background: #0b3d2e;
            color: white;
        }

        .btn-dark:hover {
            background: #145a3a;
        }

        .class-link {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            border: 1px solid #0b3d2e;
            text-decoration: none;
            color: #0b3d2e;
        }

        .class-link:hover {
            background: #0b3d2e;
            color: white;
        }

        .student-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            background: white;
        }
/* object-fit: contain; importand if you want all the page appear corecctly  */
        .student-card img {
            width: 80px;
            height: 80px;
            object-fit: cover; 
            border: 2px solid #0b3d2e;
        }

        .present-btn {
            background: #1e7d3b;
            color: white;
        }

        .absent-btn {
            background: #b02a2a;
            color: white;
        }

        .flag-btn {
            background: orange;
            color: black;
            border: none;
            padding: 8px 12px;
        }

        .error {
            color: red;
            text-align: center;
        }
        .button_return
        {
            display: block;
            width: 100%;
            text-align: center;

            padding: 12px;
            margin-top: 15px;

            background: #0b3d2e;
            color: white;

            text-decoration: none;
            border-radius: 6px;
        }

@media (max-width: 600px)
{
    .container
    {
        width: 100%;
        margin: 0;
        padding: 10px;
        border: none;
    }

    h2
    {
        font-size: 22px;
    }

    .class-list
    {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .class-link
    {
        display: block;
        width: 50px;
        padding: 14px;
        text-align: center;
        border: 2px solid #0b3d2e;
        border-radius: 8px;
        color: #0b3d2e;
        text-decoration: none;
        background: white;
    }
    .button_return
    {
        width: 150px ;
        display: block;
        text-align: center;
    }
    .student-card
    {
        flex-direction: column;
        text-align: center;
        padding: 15px;
        gap: 10px;
    }

    .student-card img
    {
        width: 100px;
        height: 100px;
        border-radius: 8px;
    }

    .student-card > div:last-child
    {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
    }

    .btn,
    .flag-btn
    {
        width: 100%;
        padding: 12px;
    }
}

    </style> -->
</head>

<body>

<div id="container">

    <h2 id = "Student_Attendance">Student Attendance</h2>

    <?php if ($user_role === 'admin'): ?>
        <div class="admin-section">
            <button id="admin-button">
                <a href="check_page.php">
                    Admin Absence Check
                </a>
            </button>
            <button id="return-page">
                <a href="../public/dashboard.php" >
                    Return to main page
                </a>
            </button>
        </div>
    
        <p id = "admin-instructions">You are admin. Choose a box to select a class.</p>
        <form method="GET">
            <select name="box_id" id="box-select">
                <option value="">-- choose box --</option>
                <?php foreach ($boxes as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= ($selectedBoxId == $b['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['box_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id = "button_select" type="submit">Select Box</button>
        </form>
    <?php else: ?>
        <p>You are teacher. Choose a class to mark attendance.</p>
    <?php endif; ?>





    <?php if (!empty($accessError)): ?>
        <p class="error"><?= htmlspecialchars($accessError) ?></p>
    <?php endif; ?>





    <?php if (!empty($classes)): ?>
        <div id="class-list">
            <?php foreach ($classes as $class): ?>
                <a href="?class_id=<?= $class['id'] ?>" id="class-names">
                    <?= htmlspecialchars($class['class_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php elseif ($user_role !== 'admin'): ?> <!-- teachers should see this if they have no assigned classes, admins will see the box selection form instead -->
        <p>No classes assigned. Ask admin.</p>
    <?php endif; ?>





    <?php if (!empty($students)): ?>
        <?php foreach ($students as $s): ?>
            <div id="student-card-container">
                <div id="student-card">
                    
                    <img src="../upload/<?= htmlspecialchars($s['photo']) ?>" alt="student">

                
                        <p id = "student-name"><?= htmlspecialchars($s['name']) ?></p>
                        <p id="class-name">Class: <?= htmlspecialchars($s['class_name']) ?></p>
                        <p id="box-name">Box: <?= htmlspecialchars($s['box_name']) ?></p>
                

                    
                        <button id="present-btn"
                            onclick="saveAttendance(<?= $s['id'] ?>,'present',this)">
                            Present
                        </button>

                        <button id="absent-btn" 
                            onclick="saveAttendance(<?= $s['id'] ?>,'absent',this)">
                            Absent
                        </button>

                        <?php if (!empty($s['has_flag'])): ?>
                            <button id="flag-btn" disabled>Flagged</button>
                        <?php endif; ?>

                        <button id="stats-btn">
                            <a id="stats-link"
                            href="student_stats.php?id=<?= $s['id'] ?>">
                                Stats
                            </a>
                        </button>
                    

                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>



</div>

<script>
function saveAttendance(studentId, status, button)
{
    fetch("save_attendance.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "student_id=" + studentId + "&status=" + status + "&hour=" + new Date().getHours()
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;

        let parent = button.closest(".student-card");
        parent.style.background = status === "present" ? "#d4f8d4" : "#ffd6d6";
    });
}
</script>

</body>
</html>