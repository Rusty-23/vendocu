<?php
require_once '../connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];

    if (isset($_FILES['documents'])) {
        $files = $_FILES['documents'];

        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = basename($files['name'][$i]);
            $targetDir = '../uploads/'; // Ensure this directory exists
            $targetFile = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                // Here, you might want to update your database to link the uploaded file to the request
                $stmt = $conn->prepare("UPDATE requested_documents SET document_link = ?, document_status = 'confirmed' WHERE request_id = ?"); 
                $stmt->bind_param("si", $targetFile, $orderId);
                $stmt->execute();
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to upload file.']);
                exit;
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No files uploaded.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
