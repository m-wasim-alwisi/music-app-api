<?php
header('Content-type: application/json');
// Artist data to be added (from request payload)
$fname = isset($_POST['fname']) ? trim($_POST['fname']) : null;
$lname = isset($_POST['lname']) ? trim($_POST['lname']) : null;
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : null;
$country = isset($_POST['country']) ? trim($_POST['country']) : null;

// Check for required data
if (!$fname || !$lname || !$gender || !$country) {
    echo "Error: Please provide all required artist data (fname, lname, gender, country).";
    exit();
}

try {
    require 'DBConnect.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
    // Prepare the SQL statement for insertion
    $sql = "INSERT INTO Artist (Fname, Lname, Gender, Country) VALUES (:fname, :lname, :gender, :country)";
    $stmt = $con->prepare($sql);

    // Bind the artist data parameters
    $stmt->bindParam(":fname", $fname, PDO::PARAM_STR);
    $stmt->bindParam(":lname", $lname, PDO::PARAM_STR);
    $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
    $stmt->bindParam(":country", $country, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();
    // Success message and potentially return the newly created artist ID

    $data = array(
        "success" => true,
        "message" => "Artist added successfully.",
        // "artist_id" => $con->lastInsertId(), // Uncomment if you want to return ID
    );

    // Encode data to JSON format
    echo json_encode($data);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $con = null;
}