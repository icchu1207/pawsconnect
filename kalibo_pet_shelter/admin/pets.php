<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add_pet'])) {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $temperament = $_POST['temperament'] ?? '';
    $location = $_POST['location'] ?? '';
    $image = '';

    $uploadDir = '../upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }

    $stmt = $conn->prepare("INSERT INTO pets (name, type, breed, age, gender, temperament, location, image) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss", $name, $type, $breed, $age, $gender, $temperament, $location, $image);
    $stmt->execute();
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM adoptions WHERE pet_id=$id");
    mysqli_query($conn, "DELETE FROM matches WHERE pet_id=$id");

    $res = mysqli_query($conn, "SELECT image FROM pets WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['image'] && file_exists("../upload/" . $row['image'])) {
        unlink("../upload/" . $row['image']);
    }

    
    mysqli_query($conn, "DELETE FROM pets WHERE id=$id");
}

$pets = [];
$result = mysqli_query($conn, "SELECT * FROM pets ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pets[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Pets - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg,#ffd1dc,#ffe6f0); padding: 20px; }
        h1 { text-align:center; color:#e91e63; margin-bottom:30px; }
        form { background:#fff; padding:20px; border-radius:10px; margin-bottom:40px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        label { font-weight:bold; color:#e91e63; }
        .pet-card { background:#fff; border-radius:10px; padding:15px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-bottom:20px; }
        .pet-card img { width:100%; height:180px; object-fit:cover; border-radius:8px; }
        .btn-delete { background:#ff5c5c; color:#fff; border:none; padding:5px 10px; border-radius:5px; margin-right:5px; }
        .btn-delete:hover { background:#e91e63; }
        .btn-edit { background:#5c9dff; color:#fff; border:none; padding:5px 10px; border-radius:5px; }
        .btn-edit:hover { background:#1e63e9; }
    </style>
</head>
<body>

<h1>Manage Pets</h1>

<form method="post" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col"><label>Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="col"><label>Type</label>
            <select name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option>Dog</option>
                <option>Cat</option>
                <option>Bird</option>
            </select>
        </div>
        <div class="col"><label>Breed</label><input type="text" name="breed" class="form-control"></div>
    </div>
    <div class="row mb-3">
        <div class="col"><label>Age</label><input type="text" name="age" class="form-control"></div>
        <div class="col"><label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">Select Gender</option>
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>
        <div class="col"><label>Temperament</label>
            <select name="temperament" class="form-control">
                <option value="">Select Temperament</option>
                <option>Friendly</option>
                <option>Calm</option>
                <option>Playful</option>
                <option>Shy</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col"><label>Location</label><input type="text" name="location" class="form-control"></div>
        <div class="col"><label>Image</label><input type="file" name="image" class="form-control"></div>
    </div>
    <button type="submit" name="add_pet" class="btn btn-primary">Add Pet</button>
</form>


<div class="row">
    <?php if (!empty($pets)): ?>
        <?php foreach($pets as $pet): ?>
            <div class="col-md-4">
                <div class="pet-card">
                    <img src="<?= isset($pet['image']) && !empty($pet['image']) && file_exists("../upload/".$pet['image']) ? "../upload/".$pet['image'] : "../assets/images/placeholder.png" ?>" alt="<?= htmlspecialchars($pet['name'] ?? 'Pet') ?>">
                    <h4><?= htmlspecialchars($pet['name'] ?? 'N/A') ?></h4>
                    <p><strong>Type:</strong> <?= htmlspecialchars($pet['type'] ?? 'N/A') ?></p>
                    <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed'] ?? 'N/A') ?></p>
                    <p><strong>Age:</strong> <?= htmlspecialchars($pet['age'] ?? 'N/A') ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($pet['gender'] ?? 'N/A') ?></p>
                    <p><strong>Temperament:</strong> <?= htmlspecialchars($pet['temperament'] ?? 'N/A') ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($pet['location'] ?? 'N/A') ?></p>
                    <a href="edit_pet.php?id=<?= $pet['id'] ?>" class="btn-edit">Edit</a>
                    <a href="?delete=<?= $pet['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; width:100%; color:#fff;">No pets have been added yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
