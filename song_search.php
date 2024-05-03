<?php
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

if (!$search) {
    echo json_encode(array("error" => "Please provide a search term."));
    exit();
}

try {
    // Connect to the database
    require 'DBConnect.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
    // Prepare the SQL statement with LIKE operator for partial title search
    $sql = "SELECT * FROM songs WHERE Title LIKE :search";
    $stmt = $con->prepare($sql);

    // Bind the search term with wildcard characters
    $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch all matching songs
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Response data
    $data = array(
        "success" => true,
        "data" => $results,
    );
    header('Content-type: application/json');

    // Encode data to JSON format
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error: " . $e->getMessage()));
    // Response data
    $data = array(
        "success" => false,
        "data" => [],
    );
    // Encode data to JSON format
    echo json_encode($data);
} finally {
    $con = null;
}
