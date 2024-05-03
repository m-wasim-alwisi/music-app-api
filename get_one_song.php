<?php


// Song ID to retrieve (passed via GET parameter)
$songId = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$songId) {
    echo "Error: Please provide a song ID.";
    exit();
}
require 'DBConnect.php';
try {
    // Prepare the SQL statement
    $sql = "SELECT * FROM songs WHERE Id = :id";
    $stmt = $con->prepare($sql);

    // Bind the song ID parameter
    $stmt->bindParam(":id", $songId, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch the song data (if any)
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$song) {
        echo "Error: Song not found.";
        exit();
    }

    // // Response data
    // $data = array(
    //     "success" => true,
    //     "data" => $song,
    // );

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE, OPTIONS');
    header("Access-Control-Allow-Headers: Origin,Origin, X-Requested-With, Content-Type, Accept, Content-Type, Accept");


    // Encode data to JSON format
    echo json_encode($song);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $con = null;
}
