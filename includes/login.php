<?php
// User login handler - Simple MySQLi version
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

// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    // Simple validation
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['error'] = implode(", ", $errors);
        header("Location: ../login.php?error=login_failed");
        exit();
    }
    
    try {
        // Initialize database connection
        $database = new Database();
        $conn = $database->getConnection();
        
        // Check user credentials
        $email = $conn->real_escape_string($email);
        $query = "SELECT id, fullname, email, password FROM users WHERE email = '$email'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $row['password'])) {
                // Password is correct, start session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['logged_in'] = true;
                
                $_SESSION['success'] = "Welcome back, " . $row['fullname'] . "!";
                
                // Redirect to dashboard
                header("Location: ../dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $_SESSION['error'] = "Invalid email or password";
                header("Location: ../login.php?error=invalid_credentials");
                exit();
            }
        } else {
            // User doesn't exist
            $_SESSION['error'] = "Invalid email or password";
            header("Location: ../login.php?error=invalid_credentials");
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
