<?php
// functions.php - reusable functions and shared settings

// Sessions must start before any HTML is printed.
// We use a session to remember a success/error message between pages.
session_start();

// Array of allowed priority values (used for the dropdown and for validation)
$priorities = ["Low", "Medium", "High"];

// Make text safe to print in HTML (prevents XSS attacks)
function h($text) {
    return htmlspecialchars($text ?? "", ENT_QUOTES, "UTF-8");
}

// Save a message in the session so the next page can show it (flash message)
function setFlash($type, $text) {
    $_SESSION["flash"] = ["type" => $type, "text" => $text];
}

// Read the message once, then remove it so it does not show again
function getFlash() {
    if (isset($_SESSION["flash"])) {
        $flash = $_SESSION["flash"];
        unset($_SESSION["flash"]);
        return $flash;
    }
    return null;
}

// Find one task by its id. Returns the task, or false if not found.
function getTask($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->fetch();
}

// Server-side validation. Returns an array of error messages (empty = all OK).
function validateTask($title, $category, $priority, $due_date, $priorities) {
    $errors = [];

    if ($title == "") {
        $errors[] = "Title is required.";
    } elseif (mb_strlen($title) > 150) {
        $errors[] = "Title must be 150 characters or less.";
    }

    if (mb_strlen($category) > 50) {
        $errors[] = "Category must be 50 characters or less.";
    }

    if (!in_array($priority, $priorities)) {
        $errors[] = "Please choose a valid priority.";
    }

    // Due date is optional, but if given it must be a real date (YYYY-MM-DD)
    if ($due_date != "") {
        $parts = explode("-", $due_date);
        if (count($parts) != 3 || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
            $errors[] = "Due date is not a valid date.";
        }
    }

    return $errors;
}
