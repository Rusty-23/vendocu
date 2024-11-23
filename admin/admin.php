<?php

require_once '../vendor/autoload.php';
require_once '../lib/GoogleClient.php';
require_once '../connection.php'; // Include database connection
require_once './inclusion/token.php';
require_once '../require.php'; // Include the access control file
require '../lib/S3BucketClient.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

checkAccess('admin'); // Check if user is admin

// AWS S3 Configuration
$bucketName = 'vendocu-datastore';
$client = new S3BucketClient($bucketName);

// Check if there's a request to upload a document
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_document']) && isset($_POST['request_id'])) {
    $requestId = intval($_POST['request_id']);
    $file = $_FILES['uploaded_document'];

    // Check for file upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Generate a unique key for the uploaded file
        $fileName = uniqid() . '_' . basename($file['name']);
        
        try {
            // Upload file to S3
            $client->uploadFile($file['tmp_name'], $fileName);
            
            // Get the pre-signed URL for the uploaded file
            $fileUrl = $client->getPresignedUrl($fileName);

            // Update the request in the database with the S3 file URL and set status to 'confirmed'
            $stmt = $conn->prepare("UPDATE request SET document_link = ?, request_status = 'confirmed' WHERE request_id = ?");
            $stmt->bind_param("si", $fileName, $requestId);

            if ($stmt->execute()) {
                // Return success response
                echo json_encode(['success' => true, 'message' => 'Document uploaded to S3 and order confirmed successfully.']);
            } else {
                // Return error response
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            $stmt->close();
        } catch (Exception $e) {
            // Handle S3 upload error
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        // Handle file upload error
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'File upload error.']);
    }
    exit; // Terminate the script after handling the request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VenDocu - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .btn-primary {
            background-color: #135701; /* Submit button with theme color */
            border: none;
            padding: 10px 20px;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Hide email domain on mobile view */
        @media (max-width: 768px) {
            .email-domain {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include 'inclusion/navbar.php'; ?>

<!-- Body Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Pending Document Requests</h2>

    <!-- Responsive Document Requests Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch pending requests (oldest to newest)
                $stmt = $conn->prepare("
                    SELECT r.request_id, r.order_number, r.email, r.document_type, r.doc_year, r.year_level, r.semester, r.section, r.course, r.requested_at 
                    FROM request r
                    WHERE r.request_status = 'pending'
                    ORDER BY r.requested_at ASC
                ");
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are results
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        $orderNumber = $row['order_number'];
                        $email = $row['email'];
                        $requestDate = date("Y-m-d", strtotime($row['requested_at']));
                        $collapseId = "details" . $row['request_id'];

                        // Split email into local part and domain part
                        $emailParts = explode('@', $email);
                        $localPart = $emailParts[0];
                        $domainPart = '@' . $emailParts[1];

                        echo "<tr>
                                <td>{$counter}</td>
                                <td>{$orderNumber}</td>
                                <td>{$localPart}<span class='email-domain'>{$domainPart}</span></td>
                                <td>{$requestDate}</td>
                                <td>
                                    <button type='button'  style=' padding: 10px 15.7px;' class='btn btn-primary btn-sm' data-bs-toggle='collapse' data-bs-target='#{$collapseId}'>See More</button>
                                    <button type='button' style='background-color: #880808; padding: 10px 20px;' class='btn btn-danger btn-sm remove-order-btn' data-order-id='{$row['request_id']}'> Remove </button>
                                </td>
                              </tr>
                              <tr class='collapse' id='{$collapseId}'>
                                <td colspan='5'>
                                    <div class='card'>
                                        <div class='card-header'>
                                            Document Requests for Order Number: {$orderNumber}
                                        </div>
                                        <div class='card-body'>
                                            <h5>Document Details</h5>
                                            <table class='table'>
                                                <thead>
                                                    <tr>
                                                        <th>Document Type</th>
                                                        <th>Year</th>
                                                        <th>Year Level</th>
                                                        <th>Semester</th>
                                                        <th>Section</th>
                                                        <th>Course</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{$row['document_type']}</td>
                                                        <td>{$row['doc_year']}</td>
                                                        <td>{$row['year_level']}</td>
                                                        <td>{$row['semester']}</td>
                                                        <td>{$row['section']}</td>
                                                        <td>{$row['course']}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <form method='POST' enctype='multipart/form-data' class='mt-3 upload-form'>
                                                <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                                <div class='mb-3'>
                                                    <label for='uploaded_document_{$row['request_id']}' class='form-label'>Upload Document ({$row['document_type']} - Year: {$row['doc_year']}):</label>
                                                    <input type='file' class='form-control' id='uploaded_document_{$row['request_id']}' name='uploaded_document' required>
                                                </div>
                                                <button type='submit' class='btn btn-primary upload-button' style='background-color: #135701;'>Upload & Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                              </tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No pending requests found.</td></tr>";
                }

                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Document uploaded to S3 and order confirmed successfully.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.upload-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show the success modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                // Reload the page after closing the modal
                successModal._element.addEventListener('hidden.bs.modal', function () {
                    location.reload();
                });
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error uploading document.');
        });
    });
});

document.querySelectorAll('.remove-order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const requestId = this.dataset.orderId;
        if (confirm('Are you sure you want to remove this order?')) {
            fetch('remove_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ request_id: requestId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order removed successfully.');
                    location.reload(); // Reload the page to update the table
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing order.');
            });
        }
    });
});
</script>

</body>

</html>

