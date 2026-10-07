<?php
// functions.php - reusable functions and shared settings
// Sessions must start before any HTML is printed.
// We use a session to remember a success/error message between pages.
session_start();

/* We will use this array for things such as: 1. Showing priority options in a form. 
2. Checking whether the user selected a valid priority. */
$priorities = ["Low", "Medium", "High"];

// Make text safe to print in HTML (prevents XSS (Cross-Site Scripting) attacks)
function h($text) {
    return htmlspecialchars($text ?? "", ENT_QUOTES, "UTF-8");
}

// Save a message in the session so the next page can show it (flash message)/ type mean type of message 
// (success, error, etc.) and text mean the message text.
function setFlash($type, $text) {
    $_SESSION["flash"] = ["type" => $type, "text" => $text];
}

/* getflash function use for get the flash message from the session/ isset mean check if the flash message is set in the session
/ unset if the flash exist and return the flash message / if not exist return null. */
function getFlash() {
    if (isset($_SESSION["flash"])) {
        $flash = $_SESSION["flash"];
        unset($_SESSION["flash"]);
        return $flash;
    }
    return null;
}


function getTask($pdo, $id) { // $pdo mean the database connection object and $id mean the task id
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id"); // prepare a SQL statement to select a task by id
    $stmt->execute([":id" => $id]); // execute the statement with the id parameter
    return $stmt->fetch(); // fetch the result as an associative array and return it
}

// Server-side validation. Returns an array of error messages (empty = all OK).

function validateTask($title, $category, $priority, $due_date, $priorities) { 
    $errors = []; // initalize empty array to store error messages

    if ($title == "") { // check if the title is empty
        $errors[] = "Title is required."; // add an error message to the array
    } elseif (mb_strlen($title) > 150) { // check if the title is longer than 150 characters
        $errors[] = "Title must be 150 characters or less."; // add an error message to the array
    }

    if (mb_strlen($category) > 50) {
        $errors[] = "Category must be 50 characters or less.";
    }

    if (!in_array($priority, $priorities)) { // check if the priority is not in the array of valid priorities
        $errors[] = "Please choose a valid priority.";
    }

    // Due date is optional, but if given it must be a real date (YYYY-MM-DD)
    if ($due_date != "") { // check if the due date is not empty
        $parts = explode("-", $due_date); // split the due date into an array of parts  Example: $due_date = "2026-10-07"; / become - $parts[0]  // "2026" $parts[1]  // "10" $parts[2]  // "07"
        if (count($parts) != 3 || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) { // check the date is not equal to 3 or checkdate is not year, month, day  Example: $parts = ["2026", "10", "07"];
            $errors[] = "Due date is not a valid date.";
        }
    }

    return $errors;
}




/* h($text)

    This is a reusable function for safely displaying
    text inside HTML.

    $text
    = the text that we want to display.

    htmlspecialchars()
    = converts special HTML characters into safe text.

    This helps protect our website from XSS
    (Cross-Site Scripting) attacks.

    Example:

    If a user enters:

    <script>alert("Hacked!")</script>

    We do NOT want the browser to execute the script.

    htmlspecialchars() converts the special characters
    so the browser treats them as normal text.
 */



/*
    setFlash($type, $text)

    This function saves a temporary message in the session.

    A flash message is usually a message such as:

    "Task created successfully."
    "Task deleted successfully."
    "Something went wrong."

    $type
    = the type of message.

    For example:

    "success"
    "error"

    $text
    = the actual message we want to show.

    $_SESSION
    = a PHP array that stores information
      between page requests.

    $_SESSION["flash"]
    = creates a session item called "flash".

    [
        "type" => $type,
        "text" => $text
    ]

    This creates an associative array.

    Example:

    setFlash("success", "Task created successfully.");

    PHP stores:

    $_SESSION["flash"] = [
        "type" => "success",
        "text" => "Task created successfully."
    ];

    Another page can then read this message.
*/