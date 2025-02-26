<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['action'] === 'add_repair') {
            // Turn on error display for debugging
            ini_set('display_errors', 1);
            error_reporting(E_ALL);

            // Debug: Print the POST data
            echo "POST data received:<br>";
            print_r($_POST);

            // Validate that all required fields are present
            if (empty($_POST['brand_id']) || empty($_POST['start_date']) || empty($_POST['description'])) {
                die("All fields are required");
            }

            // Debug: Print the query we're about to execute
            echo "<br>Attempting to insert repair...<br>";

            // Prepare and execute the insert statement
            $stmt = $pdo->prepare("INSERT INTO repairs (brand_id, start_date, status, description) 
                                 VALUES (?, ?, 'pending', ?)");
            
            $result = $stmt->execute([
                $_POST['brand_id'],
                $_POST['start_date'],
                $_POST['description']
            ]);

            // Check if the insert was successful
            if (!$result) {
                echo "Database error: ";
                print_r($stmt->errorInfo());
                die();
            }

            // Get the last inserted ID for confirmation
            $lastId = $pdo->lastInsertId();
            echo "Successfully inserted repair with ID: " . $lastId;
            
            // Wait 3 seconds before redirecting so we can see the debug info
            echo "<br>Redirecting in 3 seconds...";
            header("refresh:3;url=repairs.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Error in process_repair.php: " . $e->getMessage());
    }
}

header("Location: repairs.php");
exit();
?>