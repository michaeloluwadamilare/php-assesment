<?php
function validateRegistration($name, $email, $password, $confirm_password) {
    $errors = [];

    // Validate name
    if(empty($name)) {
        $errors[] = "Name is required.";
    } elseif(strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long.";
    }

    // Validate email
    if(empty($email)) {
        $errors[] = "Email is required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if(empty($password)) {
        $errors[] = "Password is required.";
    } elseif(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Validate confirm password
    if(empty($confirm_password)) {
        $errors[] = "Please confirm your password.";
    } elseif($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    return $errors;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>