<?php
session_start();
require_once '../controllers/AuthController.php';

$authController = new AuthController();
$errors = [];
$success = "";

if($_POST) {
    $result = $authController->register($_POST);
    
    if($result['success']) {
        $success = $result['message'];
        $_POST = array(); // Clear form
    } else {
        $errors = $result['errors'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>User Registration</h2>

    <?php if(!empty($errors)): ?>
        <div class="error">
            <?php foreach($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
        </div>

        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>