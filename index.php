<?php
// index.php - READ: show all tasks from the database
require "db.php";
require "functions.php";

// Get every task, newest first
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
$tasks = $stmt->fetchAll();

// Get the success/error message saved in the session (if any)
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Task Manager</h1>

    <?php if ($flash): ?>
        <p class="<?= h($flash["type"]) ?>"><?= h($flash["text"]) ?></p>
    <?php endif; ?>

    <a href="create.php" class="btn">+ Add Task</a>

    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if (count($tasks) == 0): ?>
            <tr><td colspan="7">No tasks yet.</td></tr>
        <?php endif; ?>

        <?php foreach ($tasks as $task): ?>
        <tr>
            <td><?= h($task["title"]) ?></td>
            <td><?= h($task["description"]) ?></td>
            <td><?= h($task["category"]) ?></td>
            <td><?= h($task["priority"]) ?></td>
            <td><?= h($task["due_date"]) ?></td>
            <td><?= $task["completed"] ? "Done" : "Not done" ?></td>
            <td>
                <a href="edit.php?id=<?= (int)$task["id"] ?>" class="btn">Edit</a>

                <!-- Delete uses a form (POST) so it cannot be triggered by just opening a link -->
                <form action="delete.php" method="post" style="display:inline"
                      onsubmit="return confirm('Delete this task?');">
                    <input type="hidden" name="id" value="<?= (int)$task["id"] ?>">
                    <button type="submit" class="btn btn-delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
