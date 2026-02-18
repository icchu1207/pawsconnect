<?php
session_start();

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Admin@123');

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/index.php"); 
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = 1; 
        header("Location: admin/index.php"); 
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
body {
    margin:0; height:100vh;
    display:flex; justify-content:center; align-items:center;
    font-family:Arial,sans-serif;
    background: linear-gradient(135deg, #f78ca0, #f9748f, #fd868c);
    background-size:400% 400%; animation: gradientBG 15s ease infinite;
    color:white; text-align:center;
}
@keyframes gradientBG {0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.login-box {background: rgba(255,255,255,0.15); padding:30px 40px; border-radius:15px; backdrop-filter: blur(10px);}
input[type="text"], input[type="password"] {width:100%; padding:10px; margin:10px 0; border:none; border-radius:10px;}
input[type="submit"] {padding:10px 25px; border:none; border-radius:25px; background:white; color:#f9748f; font-weight:bold; cursor:pointer; transition:0.3s;}
input[type="submit"]:hover {background:#f9748f; color:white;}
.error {color:#ffdddd; margin-bottom:10px;}
</style>
</head>
<body>
<div class="login-box">
    <h1>Admin Login</h1>
    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
