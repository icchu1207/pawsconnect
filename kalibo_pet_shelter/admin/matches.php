<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


if (isset($_POST['action'], $_POST['adoption_id'], $_POST['pet_id'])) {

    $adoption_id = (int)$_POST['adoption_id'];
    $pet_id = (int)$_POST['pet_id'];

    $result_email = $conn->query("SELECT email, fullname FROM adoptions WHERE id=$adoption_id");
    $adopter = $result_email->fetch_assoc();
    $adopter_email = $adopter['email'] ?? '';
    $adopter_name = $adopter['fullname'] ?? '';

    if ($_POST['action'] === 'approve') {
        $conn->query("UPDATE adoptions SET status='Approved' WHERE id=$adoption_id");
        $conn->query("UPDATE pets SET is_available=0 WHERE id=$pet_id");

        if (!empty($adopter_email)) {
            $subject = "Your Adoption Request is Approved!";
            $message = "Hi $adopter_name,\n\nCongratulations! Your adoption request for pet ID $pet_id has been approved.\n\nThank you for adopting!";
            $headers = "From: noreply@pawsconnect.com\r\n";
            mail($adopter_email, $subject, $message, $headers);
        }

    } elseif ($_POST['action'] === 'decline') {
        $conn->query("UPDATE adoptions SET status='Declined' WHERE id=$adoption_id");
    }

    header("Location: matches.php");
    exit;
}

$sql = "
    SELECT 
        adoptions.*,
        pets.name AS pet_name,
        pets.type,
        pets.image
    FROM adoptions
    JOIN pets ON adoptions.pet_id = pets.id
    ORDER BY adoptions.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adoption Matches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ffe6f0;
            padding: 30px;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #e91e63;
            margin-bottom: 30px;
        }

        table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        th, td {
            vertical-align: middle !important;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #ff5c9d;
            color: white;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<h1>🐾 Adoption Requests</h1>

<div class="table-responsive">
<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pet</th>
            <th>Adopter</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

<?php if ($result && $result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>

<tr>
    <td><?= $row['id'] ?></td>

    <td>
        <img src="../upload/<?= htmlspecialchars($row['image'] ?? '') ?>" alt="Pet"><br>
        <strong><?= htmlspecialchars($row['pet_name'] ?? '') ?></strong><br>
        <?= htmlspecialchars($row['type'] ?? '') ?>
    </td>

    <td><?= htmlspecialchars($row['fullname'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
    <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>

    <td>
        <?php 
            $status = $row['status'] ?? 'Pending';
            $statusColor = match($status) {
                'Approved' => 'success',
                'Declined' => 'danger',
                default => 'warning'
            };
        ?>
        <span class="badge bg-<?= $statusColor ?>"><?= $status ?></span>
    </td>

    <td>
        <?php if ($status === 'Pending'): ?>
        <form method="POST" class="d-flex gap-1 justify-content-center">
            <input type="hidden" name="adoption_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="pet_id" value="<?= $row['pet_id'] ?>">

            <button name="action" value="approve" class="btn btn-success btn-sm">
                Approve
            </button>

            <button name="action" value="decline" class="btn btn-danger btn-sm">
                Decline
            </button>
        </form>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7">No adoption requests yet.</td>
</tr>
<?php endif; ?>

    </tbody>
</table>
</div>

</body>
</html>
