<?php
require_once '../connection.php'; // Include your database connection file

// Ensure the request is POST and contains the necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['request_id'])) {
        $requestId = intval($data['request_id']);

        // Update the request status to 'removed'
        $stmt = $conn->prepare("UPDATE request SET request_status = 'removed' WHERE request_id = ?");
        $stmt->bind_param("i", $requestId);

        if ($stmt->execute()) {
            // Send success response
            echo json_encode(['success' => true]);
        } else {
            // Send error response
            echo json_encode(['success' => false, 'error' => 'Failed to remove the order.']);
        }

        $stmt->close();
    } else {
        // Send error response for missing request ID
        echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    }
} else {
    // Send error response for invalid request method
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
