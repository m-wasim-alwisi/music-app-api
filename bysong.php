<?php
function buySong($user_id, $song_id, $credit_card_Number): void
{
    // Connect to database using PDO
    require 'DBConnect.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,PUT,PATCH,POST,DELETE');
    header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");

    // Start a transaction
    $con->beginTransaction();

    try {
        // Create a new invoice
        $invoiceSql = "INSERT INTO invoice (user_id, date, total, credit_card)
        VALUES (:user_id, NOW(), 0, :credit_card)";
        $invoiceStmt = $con->prepare($invoiceSql);
        $invoiceStmt->execute([':user_id' => $user_id, ':credit_card' => $credit_card_Number]);
        $invoiceId = $con->lastInsertId();

        // Get song price
        $songPriceSql = "SELECT price FROM songs WHERE id = :song_id";
        $songPriceStmt = $con->prepare($songPriceSql);
        $songPriceStmt->execute([':song_id' => $song_id]);
        $songPriceRow = $songPriceStmt->fetch(PDO::FETCH_ASSOC);

        if (!$songPriceRow) {
            throw new Exception("Song not found."); // Handle song not found case
        }

        $songPrice = $songPriceRow['price'];

        // Create order for the song
        $orderSql = "INSERT INTO orders (song_id, invoice_id) VALUES (:song_id, :invoiceId)";
        $orderStmt = $con->prepare($orderSql);
        $orderStmt->execute([':song_id' => $song_id, ':invoiceId' => $invoiceId]);

        // Update invoice total
        $updateInvoiceSql = "UPDATE invoices SET total = total + :songPrice WHERE id = :invoiceId";
        $updateInvoiceStmt = $con->prepare($updateInvoiceSql);
        $updateInvoiceStmt->execute([':songPrice' => $songPrice, ':invoiceId' => $invoiceId]);

        // Commit the transaction
        $con->commit();

    } catch (PDOException $e) {
        $con->rollBack();
    } finally {
        $con = null;
    }
}

// Check if form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $songId = $_POST['song_id'];
    $creditCardNumber = $_POST['credit_card_number']; // Corrected variable name

    // Call buySong function to process the purchase
    buySong($userId, $songId, $creditCardNumber);

    // Define response data as an associative array
    $serverResponse = array(
        "code" => http_response_code(200), // Set response code to 200 (OK)
        "status" => true,
        "message" => "Done Add", // Consider a more descriptive message
    );

    // Encode the response data as JSON and echo it
    echo json_encode($serverResponse);
} else {
    // Handle non-POST requests (optional)
    // You can throw an exception, redirect to an error page, etc.
    throw new Exception("Use POST only");
}