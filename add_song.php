<?php
header('Content-type: application/json');

// Expected data from request (replace with your actual data names)
$title = isset($_POST['title']) ? trim($_POST['title']) : null;
$type = isset($_POST['type']) ? trim($_POST['type']) : null;
$price = isset($_POST['price']) ? (float) $_POST['price'] : null;
$artist_id = isset($_POST['artist_id']) ? (int) $_POST['artist_id'] : null;

// Validation (optional, enhance as needed)
if (!$title || !$type || !$price || !$artist_id) {
    echo json_encode(array("success" => false, "message" => "Missing required data."));
    exit();
}

try {
    // Connect to the database
    require 'DBConnect.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

    // Prepare the SQL statement for insertion
    $sql = "INSERT INTO songs (Title, Type, Price, artist_id) VALUES (:title, :type, :price, :artist_id)";
    $stmt = $con->prepare($sql);

    // Bind the song data parameters
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":type", $type, PDO::PARAM_STR);
    $stmt->bindParam(":price", $price, PDO::PARAM_STR);
    $stmt->bindParam(":artist_id", $artist_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Check if insertion was successful
    $insertedId = $con->lastInsertId();
    if ($insertedId) {
        $data = array(
            "success" => true,
            "message" => "Song added successfully.",
            "id" => $insertedId, // Return the newly created song ID
        );

        echo json_encode($data);
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to add song."));
    }
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
} finally {
    $con = null;
}