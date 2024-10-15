<?php
session_start();

require_once 'connection.php'; // Include your database connection

// Function to extract email from the JSON token in the cookie
function extractEmailFromToken($token) {
    $parts = explode('.', $token);
    if (count($parts) === 3) {
        $payload = base64_decode($parts[1]);
        $data = json_decode($payload, true); // Decode JSON payload
        if (json_last_error() === JSON_ERROR_NONE && isset($data['email'])) {
            return $data['email']; // Return email if it exists
        }
    }
}

// Function to check access
function checkAccess($requiredRole) {
    global $conn; // Use the global connection variable

    if (isset($_COOKIE['g_token'])) {
        $g_token = $_COOKIE['g_token']; // Get the token from the cookie
        $email = extractEmailFromToken($g_token); // Extract email from the token

        if ($email) {
            // Check if the user is an admin
            $stmt = $conn->prepare("SELECT email FROM registrar_accounts WHERE email = ?");
            $stmt->bind_param("s", $email);          

            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User is an admin
                $_SESSION['email'] = $email; // Store email in session
                if ($requiredRole === 'student') {
                    // User is admin, redirect to index
                    header("Location: ../admin/admin.php");
                    exit();
                }
            } else {
                // User is not an admin
                if ($requiredRole === 'admin') {
                    // Redirect to index if trying to access admin page
                    header("Location: ../student/index.php");
                    exit();
                }
            }
        } else {
            header("Location: ../login.php"); // Redirect to login
            exit();
        }
    } else {
        // No authentication token found
        header("Location: ../login.php"); // Redirect to login
        exit();
    }
}
?>
