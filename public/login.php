<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

if($_SESSION['loggedin']) {
    header("Location: dashboard.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = "";

if($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($user->emailExists() && password_verify($password, $user->password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if($error): ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>