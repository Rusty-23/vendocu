<?php 
require_once '../vendor/autoload.php';
require_once '../lib/GoogleClient.php';
require_once '../connection.php'; // Include database connection
require_once './inclusion/token.php';
require_once '../require.php'; // Include the access control file
checkAccess('admin'); // Check if user is an admin
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VenDocu - Admin Dashboard (All Requests)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tab-text {
            color: black !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php include 'inclusion/navbar.php'; ?>

<!-- Body Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">User Requests</h2>

    <!-- Search Input -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search for requests...">

    <!-- Nav tabs for request statuses -->
    <ul class="nav nav-tabs" id="requestTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-text active" id="confirmed-tab" data-bs-toggle="tab" href="#confirmed" role="tab" aria-controls="confirmed" aria-selected="true">Confirmed</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-text" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">Completed</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-text" id="removed-tab" data-bs-toggle="tab" href="#removed" role="tab" aria-controls="removed" aria-selected="false">Removed</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content mt-3" id="requestTabsContent">
        <!-- Confirmed Requests Tab -->
        <div class="tab-pane fade show active" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
            <h5>Confirmed Requests</h5>
            <?php displayRequests($conn, 'confirmed'); ?>
        </div>

        <!-- Completed Requests Tab -->
        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            <h5>Completed Requests</h5>
            <?php displayRequests($conn, 'completed'); ?>
        </div>

        <!-- Removed Requests Tab -->
        <div class="tab-pane fade" id="removed" role="tabpanel" aria-labelledby="removed-tab">
            <h5>Removed Requests</h5>
            <?php displayRemovedRequests($conn); ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to filter the table rows based on search input
    document.getElementById('searchInput').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const tables = document.querySelectorAll('.table');
        
        tables.forEach(table => {
            const rows = table.getElementsByTagName('tr');
            for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
                const cells = rows[i].getElementsByTagName('td');
                let rowContainsFilter = false;

                // Check if any cell in the row contains the filter text
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(filter)) {
                        rowContainsFilter = true;
                        break;
                    }
                }

                rows[i].style.display = rowContainsFilter ? '' : 'none'; // Show or hide the row
            }
        });
    });
</script>
</body>

</html>

<?php
// Function to display requests based on status
function displayRequests($conn, $status) {
    // Fetch requests from the request table based on the provided status
    $stmt = $conn->prepare("SELECT request_id, order_number, email, document_type, requested_at FROM request WHERE request_status = ? ORDER BY requested_at ASC");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order Number</th>
                            <th>Email</th>
                            <th>Document Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $orderNumber = $row['order_number'];
            $email = $row['email'];
            $documentType = $row['document_type'];
            $requestDate = date("Y-m-d", strtotime($row['requested_at']));
            $collapseId = "details" . $row['request_id'];

            echo "<tr>
                    <td>{$counter}</td>
                    <td>{$orderNumber}</td>
                    <td>{$email}</td>
                    <td>{$documentType}</td>
                    <td>{$requestDate}</td>
                    <td>
                        <button type='button' style='padding: 10px 15.7px; background-color: #135701; color: white' class='btn btn-info btn-sm' data-bs-toggle='collapse' data-bs-target='#{$collapseId}'>See More</button>
                    </td>
                  </tr>
                  <tr class='collapse' id='{$collapseId}'>
                    <td colspan='6'>
                        <div class='card'>
                            <div class='card-header'>
                                Document Details for Order Number: {$orderNumber}
                            </div>
                            <div class='card-body'>
                                <h5>Document Information</h5>
                                <div class='accordion' id='accordion{$row['request_id']}'>";

            // Fetch document details for this order
            $stmt_details = $conn->prepare("SELECT year_level, semester, section, course, doc_year, document_link FROM request WHERE request_id = ?");
            $stmt_details->bind_param("i", $row['request_id']);
            $stmt_details->execute();
            $doc_result = $stmt_details->get_result();
            $doc_row = $doc_result->fetch_assoc();

            // Displaying document details in a table format
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Year Level</th>
                            <th>Semester</th>
                            <th>Section</th>
                            <th>Course</th>
                            <th>Document Year</th>
                            <th>Document Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$doc_row['year_level']}</td>
                            <td>{$doc_row['semester']}</td>
                            <td>{$doc_row['section']}</td>
                            <td>{$doc_row['course']}</td>
                            <td>{$doc_row['doc_year']}</td>
                            <td><a href='" . $doc_row['document_link'] . "' target='_blank'>View Document</a></td>
                        </tr>
                    </tbody>
                  </table>";

            echo "</div></div></div></td></tr>";
            $counter++;
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='alert alert-info'>No requests found for {$status} status.</div>";
    }

    $stmt->close();
}

// Function to display removed requests (without document_link)
function displayRemovedRequests($conn) {
    // Fetch requests from the request table based on the removed status
    $stmt = $conn->prepare("SELECT request_id, order_number, email, document_type, requested_at FROM request WHERE request_status = 'removed' ORDER BY requested_at ASC");
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        echo '<div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order Number</th>
                            <th>Email</th>
                            <th>Document Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $orderNumber = $row['order_number'];
            $email = $row['email'];
            $documentType = $row['document_type'];
            $requestDate = date("Y-m-d", strtotime($row['requested_at']));

            echo "<tr>
                    <td>{$counter}</td>
                    <td>{$orderNumber}</td>
                    <td>{$email}</td>
                    <td>{$documentType}</td>
                    <td>{$requestDate}</td>
                    <td></td>
                  </tr>";
            $counter++;
        }
        echo '</tbody></table></div>';
    } else {
        echo "<div class='alert alert-info'>No removed requests found.</div>";
    }

    $stmt->close();
}
?>
