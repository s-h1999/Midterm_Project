<?php
// db.php - connect to the MySQL database using PDO

$host = "localhost";
$dbname = "student_db";   // change to your database name
$username = "root";         // change to your MySQL username
$password = "";             // change to your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Show errors as exceptions so problems are easy to find
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Return each row as an associative array, e.g. $row['title']
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
