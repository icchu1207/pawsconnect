<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$reports = [];
$res = mysqli_query($conn, "SELECT * FROM stray_reports ORDER BY id DESC");
if($res){
    while($r = mysqli_fetch_assoc($res)){
        $reports[] = $r;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stray Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #ffe6f0; padding:20px; font-family:Arial,sans-serif; }
        h1 { text-align:center; color:#e91e63; margin-bottom:30px; }
        .report-card { background:#fff; border-radius:10px; padding:15px; margin-bottom:20px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        .report-card img { max-width:100%; border-radius:8px; margin-top:10px; }
    </style>
</head>
<body>
<h1>Stray Reports</h1>

<?php if(empty($reports)): ?>
    <p style="text-align:center;">No stray reports yet.</p>
<?php endif; ?>

<?php foreach($reports as $r): ?>
<div class="report-card">
    <p><strong>Name:</strong> <?= htmlspecialchars($r['reporter_name'] ?? '') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($r['reporter_email'] ?? '') ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($r['reporter_phone'] ?? '') ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($r['location'] ?? '') ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($r['description'] ?? '') ?></p>
    <?php if(isset($r['photo']) && !empty($r['photo']) && file_exists("../assets/images/".$r['photo'])): ?>
        <img src="../assets/images/<?= $r['photo'] ?>" alt="Stray Photo">
    <?php endif; ?>
</div>
<?php endforeach; ?>

</body>
</html>
