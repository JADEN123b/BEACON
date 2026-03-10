<?php
// Authentication check - include this on pages that require login
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check remember me cookie
function checkRememberMe() {
    if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
        // For now, just return false since we don't have the database setup
        // In a full implementation, this would check the session token in database
        return false;
    }
    return false;
}

// Auto-login check
checkRememberMe();

// Redirect to login if not logged in
if (!isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

// Get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'fullname' => $_SESSION['fullname'],
            'email' => $_SESSION['email']
        ];
    }
    return null;
}
?>
