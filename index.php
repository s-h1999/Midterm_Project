<?php

/*
    index.php

    This is the main page of our Task Manager.

    Its main job is to:

    1. Connect to the database.
    2. Get all tasks from MySQL.
    3. Get a flash message if there is one.
    4. Display the tasks in an HTML table.
    5. Provide Edit and Delete buttons.
*/

require "db.php";
require "functions.php";

// Get every task, newest first
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC"); // execute a SQL query to select all tasks from the database and order them by id in descending order DESC gives: id - 3 2 1 - Therefore, the newest task normally appears first.

$tasks = $stmt->fetchAll(); // fetch all the results as an associative array and store them in the $tasks variable

// get the flash message from the session and store it in the $flash variable
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
<!-- check if there is a flash message and if so, display it in a paragraph with a class that matches the type of message (success, error, etc.) and the text of the message. -->
    <?php if ($flash): ?> 
        <p class="<?= h($flash["type"]) ?>"><?= h($flash["text"]) ?></p> 
    <?php endif; ?> 
<!-- end the if statement that checks if there is a flash message -->

<!-- // create a link to the create.php page with a class of btn and the text "+ Add Task" -->
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

        <!-- // check if there are no tasks and if so, display a row with a message that says "No tasks yet." and span all 7 columns of the table -->
        <?php if (count($tasks) == 0): ?>
            <tr><td colspan="7">No tasks yet.</td></tr> 
        <?php endif; ?>

        <!-- // loop through each task in the $tasks array and display it in a row of the table with the title, description, category, priority, due date, status, and actions (Edit and Delete buttons) -->
        <?php foreach ($tasks as $task): ?> 
        <tr>
            <td><?= h($task["title"]) ?></td>
            <td><?= h($task["description"]) ?></td>
            <td><?= h($task["category"]) ?></td>
            <td><?= h($task["priority"]) ?></td>
            <td><?= h($task["due_date"]) ?></td>
            <td><?= $task["completed"] ? "Done" : "Not done" ?></td>
            <td>
                <!-- // create a link to the edit.php page with specific task id and a class of btn. -->
                <a href="edit.php?id=<?= (int)$task["id"] ?>" class="btn">Edit</a> 

                <!-- Delete uses a form (POST) so it cannot be triggered by just opening a link -->
                <form action="delete.php" method="post" style="display:inline"
                      onsubmit="return confirm('Delete this task?');">
                    <input type="hidden"
                     name="id" 
                     value="<?= (int)$task["id"] ?>">
                    <button type="submit" class="btn btn-delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>


 <!--  This button submits the form. When clicked:
1. JavaScript asks for confirmation.
2. If the user clicks OK, the form is submitted.
3. The form sends the ID to delete.php.
4. delete.php deletes the task.
 -->
