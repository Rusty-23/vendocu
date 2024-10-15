<?php
session_start();
include 'connection.php'; // Database connection

// Function to extract email from the JWT token in the cookie
function extractEmailFromToken($token) {
    // Decode the JWT token
    $parts = explode('.', $token);
    if (count($parts) === 3) {
        $payload = base64_decode($parts[1]);
        $data = json_decode($payload, true); // Decode JSON payload
        if (json_last_error() === JSON_ERROR_NONE && isset($data['email'])) {
            return $data['email']; // Return email if it exists
        }
    }
    return null; // Return null if no email is found
}

// Check if the g_token cookie is set
if (isset($_COOKIE['g_token'])) {
    $g_token = $_COOKIE['g_token']; // Get the token from the cookie
    $email = extractEmailFromToken($g_token); // Extract email from the token

    if ($email) {
        // Query the Users table to see if the email is a student
        $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email found in Users table (student)
            $_SESSION['email'] = $email; // Store email in session
            header("Location: student/request.php"); // Redirect to student side
            exit();
        }

        // Check if the email exists in the Registrar Accounts (Admin) table
        $stmt = $conn->prepare("SELECT email FROM registrar_accounts WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email found in Registrars table (admin)
            $_SESSION['email'] = $email; // Store email in session
            header("Location: admin/admin.php"); // Redirect to admin side
            exit();
        }

        // If email is not found in both tables, redirect to student side by default
        header("Location: student/index.php");
        exit();
    } else {
        echo "Invalid token or email not found.";
    }
} else {
    echo "No authentication token found.";
}

$conn->close();
?>
