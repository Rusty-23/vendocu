<?php
require_once 'connection.php'; // Include your database connection

// Fetch email from session or token
$email = $token['email']; // Assuming $token contains user data from Google Auth

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare SQL statement for request table
    $stmt_request = $conn->prepare("INSERT INTO request (email, request_status, order_number, requested_at, updated_at) VALUES (?, 'pending', ?, NOW(), '0000-00-00 00:00:00')");
    
    // Generate order number (you can adjust the format as needed)
    $order_number = uniqid('ORD');
    
    // Bind email and order number
    $stmt_request->bind_param('ss', $email, $order_number);
    
    // Execute request table insertion
    if ($stmt_request->execute()) {
        // Get the inserted request ID
        $request_id = $stmt_request->insert_id;
        
        // Prepare SQL statement for requested_documents table
        $stmt_documents = $conn->prepare("INSERT INTO requested_documents (request_id, document_type, year_level, semester, section, course, document_status, document_link, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', '', NOW(), '0000-00-00 00:00:00')");
        
        // Loop through form inputs
        foreach ($_POST['document_type'] as $index => $document_type) {
            $year_level = $_POST['year_level'][$index];
            $semester = 1; // Define the semester, assuming it's constant for now
            $section = $_POST['section'][$index];
            $course = $section; // Assuming section determines the course
            
            // Bind parameters and execute the insertion for each document request
            $stmt_documents->bind_param('isisss', $request_id, $document_type, $year_level, $semester, $section, $course);
            $stmt_documents->execute();
        }
        
        echo "Request submitted successfully!";
    } else {
        echo "Error: " . $stmt_request->error;
    }
}
?>
