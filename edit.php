<?php
// edit.php - UPDATE: edit an existing task and save the changes
require "db.php";
require "functions.php";

// Get the task id from the URL (edit.php?id=3), or from the form when saving
if (isset($_POST["id"])) { // check if the id is set in the POST request (form submission)
    $id = (int)$_POST["id"]; // cast the id to an integer to prevent SQL injection and ensure it's a valid number
} elseif (isset($_GET["id"])) { // check if the id is set in the GET request (URL parameter)
    $id = (int)$_GET["id"]; // cast the id to an integer to prevent SQL injection and ensure it's a valid number
} else {
    die("No task id given."); // if no id is provided, terminate the script and display an error message
}

// check that do we have database to edit the current task? - Load the current task from the database
$task = getTask($pdo, $id);
if (!$task) {
    die("Task not found.");
}

// Start with the values saved in the database (the form shows these)
$title       = $task["title"];
$description = $task["description"];
$category    = $task["category"];
$priority    = $task["priority"];
$due_date    = $task["due_date"];
$completed   = $task["completed"];
$errors = [];

// This code runs only when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Replace the values with what the user typed
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $category    = trim($_POST["category"]);
    $priority    = $_POST["priority"];
    $due_date    = $_POST["due_date"];
    $completed   = isset($_POST["completed"]) ? 1 : 0;   // checkbox: checked = 1

    // Validate on the server
    $errors = validateTask($title, $category, $priority, $due_date, $priorities);

    if (count($errors) == 0) {

        if ($due_date == "") {
            $due_date = null;
        }

        $sql = "UPDATE tasks
                SET title = :title, description = :description, category = :category,
                    priority = :priority, due_date = :due_date, completed = :completed
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":title"       => $title,
            ":description" => $description,
            ":category"    => $category,
            ":priority"    => $priority,
            ":due_date"    => $due_date,
            ":completed"   => $completed,
            ":id"          => $id
        ]);

        setFlash("success", "Task updated.");
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Task</h1>

    <?php if (count($errors) > 0): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <!-- Hidden field keeps the id when the form is submitted -->
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Title</label>
        <input type="text" name="title" maxlength="150" value="<?= h($title) ?>">

        <label>Description</label>
        <textarea name="description" rows="4"><?= h($description) ?></textarea>

        <label>Category</label>
        <input type="text" name="category" maxlength="50" value="<?= h($category) ?>">

        <label>Priority</label>
        <select name="priority">
            <?php foreach ($priorities as $p): ?>
                <option value="<?= h($p) ?>" <?php if ($priority == $p) echo "selected"; ?>><?= h($p) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?= h($due_date) ?>">

        <label>
            <input type="checkbox" name="completed" value="1" <?php if ($completed) echo "checked"; ?>>
            Completed
        </label>

        <button type="submit" class="btn">Save Changes</button>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
