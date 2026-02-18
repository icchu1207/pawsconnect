<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #f78ca0, #f9748f, #fd868c);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    color: white;
    text-align: center;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

a.button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 25px;
    background: white;
    color: #f9748f;
    font-weight: bold;
    text-decoration: none;
    border-radius: 25px;
    transition: 0.3s;
}
a.button:hover {
    background: #f9748f;
    color: white;
}
</style>
</head>
<body>
<h1>Welcome, Admin!</h1>
<p>This page is fully protected. Only the logged-in admin can see it.</p>
<a class="button" href="?logout=1">Logout</a>
</body>
</html>
