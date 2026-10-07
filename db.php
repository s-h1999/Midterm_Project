<?php
// db.php - connect to the MySQL database using PDO - PHP data objects

$host = "localhost";
$dbname = "student_db";   // change to your database name
$username = "root";         // change to your MySQL username
$password = "";             // change to your MySQL password

try {
    // basically means:
    // "Create a connection to MySQL, on localhost,
    // using the student_db database,
    // using UTF-8 encoding,
    // and log in using root and its password."
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);


    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$pdo->setAttribute(SETTING, VALUE);
    // use the pdo object to talk to the database, setattribute mean change a PDO setting and if something goes wrong, throw an exception.

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // comment: this is a PDO setting that tells it to return SELECT results as associative arrays by default.
    // setAttribute() mean change a PDO setting, and FETCH_ASSOC means "return results as associative arrays".
    

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


/*
    $pdo->setAttribute(PDO::ATTR_ERRMODE, 
    PDO::ERRMODE_EXCEPTION);

    $pdo = our PDO database connection object.
    setAttribute() = change a PDO setting.
    PDO::ATTR_ERRMODE = the setting for handling database errors.
    PDO::ERRMODE_EXCEPTION = if a database error happens,
    throw an exception so we can catch and handle the error.
*/


/*
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, 
    PDO::FETCH_ASSOC);

    $pdo = our PDO database connection object.
    setAttribute() = change a PDO setting.
    PDO::ATTR_DEFAULT_FETCH_MODE = the setting that controls
    how database rows are returned.
    PDO::FETCH_ASSOC = return each row as an associative array.

    Example:

    id | name | email
    1  | Saw  | saw@gmail.com

    PHP receives:

    [
        "id" => 1,
        "name" => "Saw",
        "email" => "saw@gmail.com"
    ]

    Therefore:

    $student["name"]

    gives us:

    Saw
*/
