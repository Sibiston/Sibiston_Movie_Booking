<?php
session_start();

// Get JSON input from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Store values in session
if (isset($data['item_summary']) && isset($data['snack_price']) && isset($data['total_price'])) {
    $_SESSION['item_summary'] = htmlspecialchars($data['item_summary']);
    $_SESSION['snack_price'] = (float)$data['snack_price'];
    $_SESSION['total_price'] = (float)$data['total_price'];
    
    // Send response
    echo json_encode(['status' => 'success', 'message' => 'Session data saved.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
}
?>
