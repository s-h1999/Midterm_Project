<?php
// delete.php - DELETE: remove a task from the database
require "db.php";
require "functions.php";

// Only accept requests sent from the Delete button (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) { // check if the request method is POST and if the id is set in the POST request (form submission)
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute([":id" => (int)$_POST["id"]]); 

    setFlash("success", "Task deleted."); // save a message in the session to indicate that the task was deleted successfully
}

// Always go back to the list
header("Location: index.php");
exit;


// User clicks Delete
//        ↓
// <form method="POST">
//        ↓
// POST sends id = 5
//        ↓
// delete.php receives $_POST["id"]
//        ↓
// Check: Is request POST?       YES
// Check: Does id exist?         YES
//        ↓
// prepare DELETE query
//        ↓
// put id = 5 into :id
//        ↓
// execute()
//        ↓
// Task #5 is deleted
