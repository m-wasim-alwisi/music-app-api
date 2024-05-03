<?php
// Include the database connection script
require 'DBConnect.php';

// SQL query to fetch all songs
$sql = "SELECT * FROM songs";

try {
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert to JSON format
    $json_data = json_encode($songs);

    // Set headers
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
    // Output JSON data
    echo $json_data;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $con = null;
}
