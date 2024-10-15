<?php
// Start the session
include '../connection.php';

require_once '../vendor/autoload.php';
require_once '../lib/GoogleClient.php';
require_once '../connection.php'; // Include database connection
require_once './inclusion/token.php';
require_once '../require.php'; // Include the access control file
checkAccess('student'); // Check if user is a student

// Extract the email from the token
$email = $token['email'];

// Fetch document requests for the logged-in user
$stmt = $conn->prepare("
    SELECT r.order_number, r.requested_at, r.request_status, r.document_type, r.year_level, r.semester, r.section, r.course, r.doc_year 
    FROM request r
    WHERE r.email = ?
    ORDER BY r.requested_at DESC
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all results into an array
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

$stmt->close();

// Group requests by status
$groupedRequests = [
    'pending' => [],
    'confirmed' => [],
    'completed' => [],
    'declined' => [], // For removed requests
];

foreach ($requests as $request) {
    $orderNumber = $request['order_number'];
    $status = $request['request_status'] === 'removed' ? 'declined' : $request['request_status'];
    
    // Ensure we're initializing the group if it doesn't exist
    if (!isset($groupedRequests[$status][$orderNumber])) {
        $groupedRequests[$status][$orderNumber] = [
            'requested_at' => $request['requested_at'],
            'documents' => []
        ];
    }

    // Add document to the appropriate status group
    $groupedRequests[$status][$orderNumber]['documents'][] = [
        'document_type' => $request['document_type'],
        'year_level' => $request['year_level'],
        'doc_year' => $request['doc_year'],
        'semester' => $request['semester'],
        'section' => $request['section'],
        'course' => $request['course'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VenDocu - Document List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tab-text {
            color: black !important;
            font-size: 14px !important;
        }
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .table th, .table td {
                font-size: 0.8rem; /* Smaller font size for mobile */
                padding: 4px; /* Reduced padding */
            }
            h2 {
                font-size: 1.5rem; /* Smaller heading size */
            }
        }
        .collapse {
            overflow: hidden; /* Prevent content overflow */
        }
        .collapse-inner {
            max-width: 100%; /* Ensure inner content does not exceed */
            overflow-x: auto; /* Allow horizontal scrolling if necessary */
        }
        .collapse table {
            width: 100%; /* Inner table fills available width */
            table-layout: auto; /* Responsive table layout */
        }
        .btn-see-more {
            margin-top: 0.5rem; /* Spacing for the button */
        }
        .container {
            overflow: hidden;
        }
    </style>
</head>
<body>

<!-- Navbar start -->
<?php include 'inclusion/navbar.php'; ?>
<!-- Navbar end -->

<!-- Body Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Document Requests</h2>

    <!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs" id="requestTabs" role="tablist">
        <?php foreach (['pending', 'confirmed', 'completed', 'declined'] as $status): ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link tab-text <?= $status === 'pending' ? 'active' : ''; ?>" 
                   id="<?= $status ?>-tab" 
                   data-bs-toggle="tab" 
                   href="#<?= $status ?>" 
                   role="tab" 
                   aria-controls="<?= $status ?>" 
                   aria-selected="<?= $status === 'pending' ? 'true' : 'false'; ?>">
                   <?= ucfirst($status) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content" id="requestTabsContent">
        <?php foreach ($groupedRequests as $status => $requests): ?>
            <div class="tab-pane fade <?= $status === 'pending' ? 'show active' : ''; ?>" id="<?= $status ?>" role="tabpanel" aria-labelledby="<?= $status ?>-tab">
                <?php if (!empty($requests)): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($requests as $orderNumber => $data): 
                                $collapseId = "collapse" . ucfirst($status) . $orderNumber;
                            ?>
                                <tr>
                                    <td><?php echo $orderNumber; ?></td>
                                    <td><?php echo date("Y-m-d", strtotime($data['requested_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm btn-see-more" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>">
                                            See More
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="<?php echo $collapseId; ?>">
                                    <td colspan="6">
                                        <div class="collapse-inner"> <!-- Added class for styling -->
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Type</th>
                                                        <th>Year Level</th>
                                                        <th>Year</th>
                                                        <th>Semester</th>
                                                        <th>Section</th>
                                                        <th>Course</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['documents'] as $document): ?>
                                                        <tr>
                                                            <td><?php echo $document['document_type']; ?></td>
                                                            <td><?php echo $document['year_level']; ?></td>
                                                            <td><?php echo $document['doc_year']; ?></td>
                                                            <td><?php echo $document['semester']; ?></td>
                                                            <td><?php echo $document['section']; ?></td>
                                                            <td><?php echo $document['course']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div> <!-- Close collapse-inner div -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        No <?= $status; ?> document requests found.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
