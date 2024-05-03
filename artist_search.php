<?php

// Search term (passed via GET parameter)
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : null;

if (!$searchTerm) {
    echo "Error: Please provide a search term.";
    exit();
}

try {

    require 'DBConnect.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

    // Prepare the SQL statement with LIKE operator for partial name search
    // Search both first and last name with OR condition
    $sql = "SELECT * FROM Artist WHERE (Fname LIKE :search OR Lname LIKE :search)";
    $stmt = $con->prepare($sql);

    // Bind the search term with wildcard characters
    $stmt->bindValue(":search", "%$searchTerm%", PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch all matching artists
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Response data
    $data = array(
        "success" => true,
        "data" => $artists,
    );
    header('Content-type: application/json');
    // Encode data to JSON format
    echo json_encode($data);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $data = array(
        "success" => false,
        "data" => [],
    );
    echo json_encode($data);

} finally {
    $con = null;
}

