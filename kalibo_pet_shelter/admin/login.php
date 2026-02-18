<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=kalibo_pet_shelter", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$error = '';

$defaultAdmin = [
    'username' => 'admin',
    'password' => 'Admin@123',
    'id' => 1
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

       
        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: index.php");
                exit;
            }
        } 

        elseif ($username === $defaultAdmin['username'] && $password === $defaultAdmin['password']) {
            $_SESSION['admin_id'] = $defaultAdmin['id'];
            $_SESSION['admin_username'] = $defaultAdmin['username'];
            header("Location: index.php");
            exit;
        }

        $error = 'Invalid username or password';
    } else {
        $error = 'Please enter both username and password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: linear-gradient(135deg, #ff9a9e, #fad0c4);
    }
    .login-box {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        width: 360px;
    }
    h3 {
        color: #ff1a75;
        text-align: center;
        margin-bottom: 25px;
    }
</style>
</head>
<body>
<div class="login-box">
    <h3>Admin Login</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>
