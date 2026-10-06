<?php
// delete.php - DELETE: remove a task from the database
require "db.php";
require "functions.php";

// Only accept requests sent from the Delete button (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute([":id" => (int)$_POST["id"]]);

    setFlash("success", "Task deleted.");
}

// Always go back to the list
header("Location: index.php");
exit;
