<?php
// create.php - CREATE: add a new task using an HTML form and POST
require "db.php";
require "functions.php";

// Start with empty values (the form shows these)
$title = "";
$description = "";
$category = "";
$priority = "Medium";
$due_date = "";
$errors = [];

// This code runs only when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get the values from the form
    $title       = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $category    = trim($_POST["category"]);
    $priority    = $_POST["priority"];
    $due_date    = $_POST["due_date"];

    // 2. Validate on the server
    $errors = validateTask($title, $category, $priority, $due_date, $priorities);

    // 3. If there are no errors, save to the database
    if (count($errors) == 0) {

        // An empty date must be saved as NULL, not as ""
        if ($due_date == "") {
            $due_date = null;
        }

        $sql = "INSERT INTO tasks (title, description, category, priority, due_date)
                VALUES (:title, :description, :category, :priority, :due_date)";
        $stmt = $pdo->prepare($sql); // prepare a SQL statement to insert a new task into the database with placeholders for the values
        $stmt->execute([
            ":title"       => $title,
            ":description" => $description,
            ":category"    => $category,
            ":priority"    => $priority,
            ":due_date"    => $due_date
        ]);

        // 4. Save a message in the session and go back to the list
        setFlash("success", "Task added.");
        header("Location: index.php"); // redirect the user to the index.php page after the task is added
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Add Task</h1>

    <!-- // loop through each error message in the $errors array and display it in a paragraph with a class of error -->
    <?php if (count($errors) > 0): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error) ?></p> 
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <label>Title</label>
        <input type="text" name="title" maxlength="150" value="<?= h($title) ?>">

        <label>Description</label>
        <textarea name="description" rows="4"><?= h($description) ?></textarea>

        <label>Category</label>
        <input type="text" name="category" maxlength="50" value="<?= h($category) ?>">

        <label>Priority</label>
        <select name="priority">
            <?php foreach ($priorities as $p): ?>
                <!-- // loop through each priority  and if the current priority matches the selected priority - priority == $p, add the "selected" mean current priority  -->
                <option value="<?= h($p) ?>" <?php if ($priority == $p) echo "selected"; ?>><?= h($p) ?></option> 
            <?php endforeach; ?>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?= h($due_date) ?>">

        <button type="submit" class="btn">Save Task</button>

        <!-- // create a link to the index.php page with a class of btn and the text "Cancel" -->
        <a href="index.php" class="btn btn-cancel">Cancel</a> 
    </form>
</div>
</body>
</html>
