<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VenDocu - Document List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .table-container {
            max-height: 400px; /* Set max height for scrolling */
            overflow-y: auto; /* Enable vertical scrolling */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="vendocu.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">List</a>
                </li>
            </ul>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Body Content -->
<div class="container mt-4">
    <h2 class="text-center mb-4">Document Requests</h2>

    <!-- Search Bar -->
    <div class="mb-3 d-flex justify-content-end">
        <input type="text" class="form-control w-25" id="searchBar" placeholder="Search...">
    </div>

    <!-- Hardcoded Document Requests -->
    <div class="mb-4">
        <h5>Order Number: 123456 - Status: Pending</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>1</td>
                        <td>COG</td>
                        <td>2024</td>
                        <td>2</td>
                        <td>1</td>
                        <td>A</td>
                        <td>BSIT</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <h5>Order Number: 654321 - Status: Completed</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>1</td>
                        <td>COR</td>
                        <td>2024</td>
                        <td>1</td>
                        <td>2</td>
                        <td>B</td>
                        <td>BSOA</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <h5>Order Number: 789012 - Status: Cancelled</h5>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
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
                        <td>1</td>
                        <td>COG</td>
                        <td>2024</td>
                        <td>3</td>
                        <td>1</td>
                        <td>C</td>
                        <td>HRM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- No requests message -->
    <div class="alert alert-warning" role="alert" style="display: none;">
        No document requests found.
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<!-- Custom JS -->
<script>
    document.getElementById('searchBar').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                const rowContainsSearchValue = Array.from(cells).some(cell => 
                    cell.innerText.toLowerCase().includes(searchValue)
                );
                row.style.display = rowContainsSearchValue ? '' : 'none';
            });
        });
    });
</script>

</body>
</html>
