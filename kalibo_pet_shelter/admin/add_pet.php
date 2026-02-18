<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

?>

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../admin_login.php"); 
    exit;
}
?>

include '../config.php';

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

    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['name'] != ''){
        $photo = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/'.$photo);
    }
    $stmt = $conn->prepare("INSERT INTO pets (name, breed, shelter_location, description, size, age, behavior, temperament, activity_level, photo, available) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$name, $breed, $shelter_location, $description, $size, $age, $behavior, $temperament, $activity_level, $photo, $available]);
    header("Location: pets.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Add New Pet</h1>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Breed</label>
            <input type="text" name="breed" class="form-control">
        </div>

        <div class="mb-3">
            <label>Shelter Location</label>
            <input type="text" name="shelter_location" class="form-control">
        </div>

        <div class="mb-3">
            <label>Description / Background Story</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Size</label>
            <select name="size" class="form-control">
                <option value="Small">Small</option>
                <option value="Medium">Medium</option>
                <option value="Large">Large</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" class="form-control">
        </div>

        <div class="mb-3">
            <label>Behavior</label>
            <input type="text" name="behavior" class="form-control">
        </div>

        <div class="mb-3">
            <label>Temperament</label>
            <select name="temperament" class="form-control">
                <option value="Friendly">Friendly</option>
                <option value="Aggressive">Aggressive</option>
                <option value="Shy">Shy</option>
                <option value="Playful">Playful</option>
                <option value="Calm">Calm</option>
                <option value="Energetic">Energetic</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Activity Level</label>
            <select name="activity_level" class="form-control">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Photo</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="available" class="form-check-input" checked>
            <label class="form-check-label">Available</label>
        </div>

        <button type="submit" name="submit" class="btn btn-success">Add Pet</button>
        <a href="pets.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
