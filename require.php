<?php
session_start();

require_once 'connection.php'; // Include your database connection

// Function to extract email from the JSON token in the cookie

function extractEmailFromToken($token)
{
    $googleClient = new GoogleClient(
        "VenDocu",
        "70089890797-e9pmqvs239pog7ltvq054sgo0k88r351.apps.googleusercontent.com",
        "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",
        "http://localhost/xampploc/black/home.php"
    );

    $googleClient->getClient()->addScope("email");
    $googleClient->getClient()->addScope("profile");

    $token = $googleClient->getClient()->verifyIdToken($token);
    if (!$token) {
        return null;
    }
    return $token['email'];
}

// Function to check access
function checkAccess($requiredRole)
{
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
            header("Location: ../login.php?message=Invalid authentication token"); // Redirect to login
            exit();
        }
    } else {
        // No authentication token found
        header("Location: ../login.php?message=Authentication token not found"); // Redirect to login
        exit();
    }
}
