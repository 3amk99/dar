<?php
session_start();
require_once "../config/config.php";


$db = $data;

$users = $db->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);

$classes = $db->query("SELECT id, class_name FROM classes")->fetchAll(PDO::FETCH_ASSOC);

$selectedUser = $_GET['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $user_id = (int)$_POST['user_id'];
    $selected = $_POST['classes'] ?? [];

    $stmt = $db->prepare("DELETE FROM user_class WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);

    $ins = $db->prepare("INSERT INTO user_class (user_id, class_id) VALUES (:user_id, :class_id)");

    foreach ($selected as $class_id)
    {
        $ins->execute([
            ':user_id' => $user_id,
            ':class_id' => (int)$class_id
        ]);
    }

    header('Location: assign_classes.php?user_id=' . $user_id);
    exit;
}


$current = [];

if ($selectedUser)
{
    $stmt = $db->prepare("SELECT class_id FROM user_class WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $selectedUser]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $r)
    {
        $current[] = $r['class_id'];
    }
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Classes</title>

    
    <link rel="stylesheet" href="../admin/CSS/assign_classes.css">
</head>

<body>


<div id="page">

<h2 id="title">Assign Classes to Users</h2>

<p>
    <a id="back-link" href="../public/dashboard.php">Back to dashboard</a>
</p>

<form method="get" id="user-select-form">
    <label>
        Select user:

        <select name="user_id" onchange="this.form.submit()" id="user-select">

            <option value="">-- choose user --</option>

            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>" <?= ($selectedUser == $u['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['username']) ?>
                </option>
            <?php endforeach; ?>

        </select>
    </label>
</form>

<?php if ($selectedUser): ?>

<form method="post" id="assign-form">

    <input type="hidden" name="user_id" value="<?= $selectedUser ?>">

    <div id="class-list">

        <?php foreach ($classes as $c): ?>

            <label class="class-item">

                <input type="checkbox"
                       name="classes[]"
                       value="<?= $c['id'] ?>"
                       <?= in_array($c['id'], $current) ? 'checked' : '' ?>>

                <?= htmlspecialchars($c['class_name']) ?>

            </label>

        <?php endforeach; ?>

    </div>

    <button type="submit" id="save-btn">Save assignments</button>

</form>

<?php else: ?>

<p id="hint">Please select a user to assign classes.</p>

<?php endif; ?>

</div>

</body>
</html>