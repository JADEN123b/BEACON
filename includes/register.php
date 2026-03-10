<?php
// User registration handler - Simple MySQLi version
session_start();

// Include MySQLi database configuration
require_once '../config/mysqli_db.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle registration request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = sanitize_input($_POST['fullname']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Simple validation
    $errors = [];
    
    if (empty($fullname)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['error'] = implode(", ", $errors);
        header("Location: ../login.php?error=registration_failed");
        exit();
    }
    
    try {
        // Initialize database connection
        $database = new Database();
        $conn = $database->getConnection();
        
        // Check if email already exists
        $email = $conn->real_escape_string($email);
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($check_query);
        
        if ($result && $result->num_rows > 0) {
            $_SESSION['error'] = "Email already exists";
            header("Location: ../login.php?error=email_exists");
            exit();
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $fullname = $conn->real_escape_string($fullname);
        $hashed_password = $conn->real_escape_string($hashed_password);
        $insert_query = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
        
        if ($conn->query($insert_query)) {
            $_SESSION['success'] = "Registration successful! Please log in.";
            header("Location: ../login.php?success=registered");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: ../login.php?error=registration_failed");
            exit();
        }
        
    } catch(Exception $exception) {
        $_SESSION['error'] = "Database error: " . $exception->getMessage();
        header("Location: ../login.php?error=database_error");
        exit();
    }
} else {
    // If not POST request, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>
