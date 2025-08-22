<?php
session_start();
require_once '../controllers/AuthController.php';

// Redirect if already logged in
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$authController = new AuthController();
$error = "";

if($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $authController->login($email, $password);
    
    if($result['success']) {
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['user_name'] = $result['user']['name'];
        $_SESSION['user_email'] = $result['user']['email'];
        $_SESSION['loggedin'] = true;
        
        header("Location: dashboard.php");
        exit;
    } else {
        $error = $result['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            padding: 12px 25px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #f9f9f9;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php if($error): ?>
            <div class="error">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
            <div class="success">
                <p>Registration successful! Please login with your credentials.</p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" 
                       required autocomplete="current-password">
            </div>

            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>