<?php
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../includes/validation.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data) {
        $response = [
            'success' => false,
            'errors' => [],
            'message' => ''
        ];

        try {
            // Sanitize inputs
            $name = sanitizeInput($data['name']);
            $email = sanitizeInput($data['email']);
            $password = $data['password'];
            $confirm_password = $data['confirm_password'];

            // Validate inputs
            $validation_errors = validateRegistration($name, $email, $password, $confirm_password);

            // Check if email already exists
            $this->user->email = $email;
            if($this->user->emailExists()) {
                $validation_errors[] = "Email already exists.";
            }

            if (!empty($validation_errors)) {
                $response['errors'] = $validation_errors;
                return $response;
            }

            // Create user
            $this->user->name = $name;
            $this->user->password = $password;

            if ($this->user->create()) {
                $response['success'] = true;
                $response['message'] = "Registration successful! Proceed to login.";
            } else {
                $response['errors'][] = "Unable to register. Please try again.";
            }

        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $response['errors'][] = "An unexpected error occurred. Please try again.";
        }

        return $response;
    }

    /**
     * Handle user login
     * @param string $email
     * @param string $password
     * @return array - Login result with user data if successful
     */
    public function login($email, $password) {
        $response = [
            'success' => false,
            'error' => '',
            'user' => null
        ];

        try {
            // Validate inputs
            if (empty($email) || empty($password)) {
                $response['error'] = "Email and password are required.";
                return $response;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['error'] = "Invalid email format.";
                return $response;
            }

            // Check if user exists
            $this->user->email = sanitizeInput($email);
            
            if (!$this->user->emailExists()) {
                $response['error'] = "Invalid email or password.";
                return $response;
            }

            // Verify password
            if (password_verify($password, $this->user->password)) {
                $response['success'] = true;
                $response['user'] = [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $email
                ];
            } else {
                $response['error'] = "Invalid email or password.";
            }

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $response['error'] = "An unexpected error occurred. Please try again.";
        }

        return $response;
    }

    public function validateSession() {
        session_start();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
?>