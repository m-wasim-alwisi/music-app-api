<?php
// Include the database connection script

#=> http://127.0.0.1/foo
require 'DBConnect.php';



// SQL query to fetch all artists
$sql = "SELECT * FROM artist";

try {
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert to JSON format
    $json_data = json_encode($artists);

    // Set headers
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
    // Output JSON data
    // echo $json_data;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $con = null;
}

