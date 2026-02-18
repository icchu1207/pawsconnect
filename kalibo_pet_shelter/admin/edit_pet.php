<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include '../config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM pets WHERE id=?");
$stmt->execute([$id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $shelter_location = $_POST['shelter_location'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $age = $_POST['age'];
    $behavior = $_POST['behavior'];
    $temperament = $_POST['temperament'];
    $activity_level = $_POST['activity_level'];
    $available = isset($_POST['available']) ? 1 : 0;

    $photo = $pet['photo'];
    if(isset($_FILES['photo']) && $_FILES['photo']['name'] != ''){
        $photo = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/'.$photo);
    }
    $update = $conn->prepare("UPDATE pets 
        SET name=?, breed=?, shelter_location=?, description=?, size=?, age=?, behavior=?, temperament=?, activity_level=?, photo=?, available=? 
        WHERE id=?");
    $update->execute([$name, $breed, $shelter_location, $description, $size, $age, $behavior, $temperament, $activity_level, $photo, $available, $id]);
    header("Location: pets.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-5 mb-5">
    <h1 class="text-center mb-4">Edit Pet</h1>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($pet['name']) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Breed</label>
            <input type="text" name="breed" value="<?= htmlspecialchars($pet['breed']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Shelter Location</label>
            <input type="text" name="shelter_location" value="<?= htmlspecialchars($pet['shelter_location']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Description / Background Story</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($pet['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-control">
                <option value="Small" <?= $pet['size']=='Small'?'selected':'' ?>>Small</option>
                <option value="Medium" <?= $pet['size']=='Medium'?'selected':'' ?>>Medium</option>
                <option value="Large" <?= $pet['size']=='Large'?'selected':'' ?>>Large</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" value="<?= htmlspecialchars($pet['age']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Behavior</label>
            <input type="text" name="behavior" value="<?= htmlspecialchars($pet['behavior']) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Temperament</label>
            <select name="temperament" class="form-control">
                <?php
                $options = ['Friendly','Aggressive','Shy','Playful','Calm','Energetic'];
                foreach($options as $opt){
                    $selected = $pet['temperament']==$opt?'selected':'';
                    echo "<option value=\"$opt\" $selected>$opt</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Activity Level</label>
            <select name="activity_level" class="form-control">
                <option value="Low" <?= $pet['activity_level']=='Low'?'selected':'' ?>>Low</option>
                <option value="Medium" <?= $pet['activity_level']=='Medium'?'selected':'' ?>>Medium</option>
                <option value="High" <?= $pet['activity_level']=='High'?'selected':'' ?>>High</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Photo</label><br>
            <?php if($pet['photo']): ?>
                <img src="../assets/images/<?= htmlspecialchars($pet['photo']) ?>" width="150" class="rounded">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Change Photo</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="available" class="form-check-input" <?= $pet['available']?'checked':'' ?>>
            <label class="form-check-label">Available</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="submit" class="btn btn-success flex-fill">Update Pet</button>
            <a href="pets.php" class="btn btn-secondary flex-fill">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
