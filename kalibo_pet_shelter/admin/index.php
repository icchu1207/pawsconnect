<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$totalPets = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pets"))['total'] ?? 0;
$totalMatches = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM adoptions WHERE status='Approved'"))['total'] ?? 0;
$totalPending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM adoptions WHERE status='Pending'"))['total'] ?? 0;
$totalStrays = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM stray_reports"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: linear-gradient(135deg, #ffd1dc, #ffe6f0);
        min-height: 100vh;
    }

    .header {
        text-align: center;
        padding: 40px 20px 20px;
    }

    .header h1 {
        color: #e91e63;
        font-size: 42px;
        margin: 0;
    }

    .cards {
        display: flex;
        justify-content: center;
        gap: 30px;
        padding: 40px 20px;
        flex-wrap: wrap;
    }

    .card {
        background: #ffffff;
        width: 260px;
        padding: 30px 20px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h2 {
        margin: 0 0 15px;
        color: #e91e63;
        font-size: 20px;
    }

    .card .number {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card a {
        display: inline-block;
        padding: 10px 22px;
        border-radius: 8px;
        background: #ff5c9d;
        color: white;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }

    .card a:hover {
        background: #e91e63;
    }

    .logout {
        text-align: center;
        margin-top: 20px;
    }

    .logout a {
        color: #555;
        text-decoration: none;
        font-weight: bold;
    }

    .logout a:hover {
        color: #e91e63;
    }
</style>
</head>
<body>

<div class="header">
    <h1>Admin Dashboard</h1>
</div>

<div class="cards">

    <div class="card">
        <h2>Total Pets</h2>
        <div class="number"><?= $totalPets ?></div>
        <a href="pets.php">Manage Pets</a>
    </div>

    <div class="card">
        <h2>Approved Matches</h2>
        <div class="number"><?= $totalMatches ?></div>
        <a href="matches.php">View Matches</a>
    </div>

    <div class="card">
        <h2>Pending Approvals</h2>
        <div class="number"><?= $totalPending ?></div>
        <a href="matches.php">Approve Requests</a>
    </div>

    <div class="card">
        <h2>Stray Reports</h2>
        <div class="number"><?= $totalStrays ?></div>
        <a href="stray_reports.php">View Reports</a>
    </div>

</div>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
