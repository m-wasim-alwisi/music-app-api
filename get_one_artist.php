<?php


// Song ID to retrieve (passed via GET parameter)
$artistId = isset($_GET['id']) ? (int) $_GET['id'] : null;

if (!$artistId) {
    echo "Error: Please provide a song ID.";
    exit();
}
require 'DBConnect.php';
try {
    // Prepare the SQL statement
    $sql = "SELECT * FROM artist WHERE Id = :id";
    $stmt = $con->prepare($sql);

    // Bind the artist ID parameter
    $stmt->bindParam(":id", $artistId, PDO::PARAM_INT, 255);

    // Execute the query
    $stmt->execute();

    // Fetch the artist data (if any)
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$artist) {
        echo "Error: artist not found.";
        exit();
    }

    // // Response data
    // $data = array(
    //     "success" => true,
    //     "data" => $artist,
    // );

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

    http_response_code(200);
    // Encode data to JSON format
    echo json_encode($artist);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    http_response_code(404);

} finally {
    $con = null;
}
