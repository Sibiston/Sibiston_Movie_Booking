<?php
session_start();

// Get the JSON input from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['seatNumbers']) && isset($data['totalPrice'])) {
    // Store seat numbers and total price in session
    $_SESSION['selected_seat_numbers'] = htmlspecialchars($data['seatNumbers']);
    $_SESSION['seat_price'] = htmlspecialchars($data['totalPrice']);

    // Send success response
    echo json_encode(['message' => 'Seat data saved successfully']);
} else {
    // Send error response
    echo json_encode(['message' => 'Failed to save seat data']);
}
?>
