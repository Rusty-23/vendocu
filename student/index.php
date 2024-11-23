<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../vendor/autoload.php';
require_once '../lib/GoogleClient.php';
require_once '../connection.php'; // Include database connection
require_once './inclusion/token.php';
require_once '../require.php'; // Include the access control file
checkAccess('student'); // Check if user is a student


function generateUniqueOrderNumber($conn)
{
    $isUnique = false;
    $orderNumber = '';

    while (!$isUnique) {
        $suffix = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $orderNumber = 'ORD-' . $suffix;

        $stmt = $conn->prepare("SELECT COUNT(*) FROM request WHERE order_number = ?");
        $stmt->bind_param("s", $orderNumber);
        $stmt->execute();
        
        $count = 0; 
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $isUnique = true;
        }
    }

    return $orderNumber;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $token['email'];
    $orderNumber = generateUniqueOrderNumber($conn);

    // Check if the student is irregular and handle accordingly
    if (isset($_POST['studentType']) && $_POST['studentType'] === 'irregular') {
        foreach ($_POST['subjects'] as $index => $subject) {
            $section = $_POST['sections'][$index];
            $docYear = $_POST['docYear'][0]; // Assuming same for all
            $yearLevel = $_POST['yearLevel'][0]; // Assuming same for all
            $semester = $_POST['semester'][0]; // Assuming same for all

            // Insert irregular subjects into request table
            $stmt = $conn->prepare("INSERT INTO request (email, order_number, document_type, doc_year, year_level, semester, section, course, requested_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            if ($stmt) {
                $documentType = 'IRREGULAR'; // Use your desired document type for irregular requests
                $stmt->bind_param("sssiisss", $email, $orderNumber, $documentType, $docYear, $yearLevel, $semester, $section, $subject);
                if (!$stmt->execute()) {
                    echo "Error executing statement: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
    } else {
        foreach ($_POST['documentType'] as $index => $documentType) {
            $yearLevel = $_POST['yearLevel'][$index];
            $docYear = isset($_POST['docYear'][$index]) ? $_POST['docYear'][$index] : null;
            $semester = $_POST['semester'][$index];
            $section = $_POST['section'][$index];
            $course = $_POST['course'][$index];

            // Insert regular document request
            if ($docYear !== null) {
                $stmt = $conn->prepare("INSERT INTO request (email, order_number, document_type, doc_year, year_level, semester, section, course, requested_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                if ($stmt) {
                    $stmt->bind_param("sssiisss", $email, $orderNumber, $documentType, $docYear, $yearLevel, $semester, $section, $course);
                    if (!$stmt->execute()) {
                        echo "Error executing statement: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            } else {
                echo "Document Year is required.";
            }
        }
    }

    $conn->close();
    header("Location: ./index.php?success=1");
    exit();
}
?>

<!-- navbar start-->
<?php include 'inclusion/navbar.php'; ?>
<!-- navbar end-->

<!-- Body Content -->
<div class="container mt-5">
    <h2 class="text-center mb-4" style="color: #135701; font-size: 2.5rem; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">Document Request Form</h2>

    <div class="card border-0 shadow-lg p-4" style="border-radius: 1rem;">
        <div class="card-body">
            <form id="requestForm" method="POST">
                <div class="row mb-3 g-3">
                    <div class="col-12 col-md-6">
                        <select class="form-select" name="documentType[]" required>
                            <option value="" disabled selected>Select Document Type</option>
                            <option value="COG">COG</option>
                            <option value="COR">COR</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="number" class="form-control" name="docYear[]" placeholder="Document Year" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-select" name="yearLevel[]" required>
                            <option value="" disabled selected>Year Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <select class="form-select" name="semester[]" required>
                            <option value="" disabled selected>Semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control" name="section[]" placeholder="Section" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="text" class="form-control" name="course[]" placeholder="Course" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Student Type:</label><br>
                    <input type="radio" name="studentType" value="regular" checked> Regular
                    <input type="radio" name="studentType" value="irregular"> Irregular
                </div>

                <div id="irregularFields" style="display:none;">
                    <h5>Irregular Subjects</h5>
                    <div id="subjectContainer">
                        <div class="row mb-2">
                            <div class="col-6">
                                <input type="text" class="form-control" name="subjects[]" placeholder="Subject" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" name="sections[]" placeholder="Section" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" id="addSubjectButton">Add Another Subject</button>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-lg w-100" style="padding: 15px; background-color: #135701; color: #fff;">Submit Request</button>
                </div>
            </form>
        </div>
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
                Your document request has been submitted successfully!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Custom JS -->
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }

    const studentTypeRadios = document.querySelectorAll('input[name="studentType"]');
    const irregularFields = document.getElementById('irregularFields');
    const subjectContainer = document.getElementById('subjectContainer');

    studentTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'irregular') {
                irregularFields.style.display = 'block';
            } else {
                irregularFields.style.display = 'none';
            }
        });
    });

    document.getElementById('addSubjectButton').addEventListener('click', () => {
        const newSubjectRow = document.createElement('div');
        newSubjectRow.classList.add('row', 'mb-2');
        newSubjectRow.innerHTML = `
            <div class="col-6">
                <input type="text" class="form-control" name="subjects[]" placeholder="Subject" required>
            </div>
            <div class="col-6">
                <input type="text" class="form-control" name="sections[]" placeholder="Section" required>
            </div>
        `;
        subjectContainer.appendChild(newSubjectRow);
    });
</script>

</body>
</html>
