<?php
include 'config.php';

$adopterStmt = $conn->prepare("INSERT INTO adopters 
(name,email,phone,preferred_breed,preferred_size,preferred_age,preferred_behavior,preferred_temperament,preferred_activity_level)
VALUES (?,?,?,?,?,?,?,?,?)");

$adopterStmt->execute([
    $_POST['name'],
    $_POST['email'],
    $_POST['phone'],
    $_POST['preferred_breed'],
    $_POST['preferred_size'],
    $_POST['preferred_age'],
    $_POST['preferred_behavior'],
    $_POST['preferred_temperament'],
    $_POST['preferred_activity_level']
]);

$adopter_id = $conn->lastInsertId();

$matchStmt = $conn->prepare("SELECT * FROM pets WHERE available=1 
AND (breed LIKE ? OR ?='') 
AND size=? 
AND age<=? 
AND behavior LIKE ? 
AND temperament LIKE ? 
AND activity_level=?");

$matchStmt->execute([
    '%' . $_POST['preferred_breed'] . '%',
    $_POST['preferred_breed'],
    $_POST['preferred_size'],
    $_POST['preferred_age'],
    '%' . $_POST['preferred_behavior'] . '%',
    '%' . $_POST['preferred_temperament'] . '%',
    $_POST['preferred_activity_level']
]);

$matches = $matchStmt->fetchAll(PDO::FETCH_ASSOC);

foreach($matches as $pet) {
    $insertMatch = $conn->prepare("INSERT INTO matches (adopter_id, pet_id) VALUES (?, ?)");
    $insertMatch->execute([$adopter_id, $pet['id']]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Matches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffe6f0, #ffcce6);
            min-height: 100vh;
        }

        h1 {
            color: #ff1a75;
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 1px 1px 3px #ff66b3;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card-title {
            color: #ff1a75;
            font-weight: bold;
        }

        .card-text {
            color: #333;
        }

        .btn-back {
            background: linear-gradient(45deg, #ff66b3, #ff1a75);
            color: white;
            font-weight: bold;
            border: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background: linear-gradient(45deg, #ff1a75, #ff66b3);
            color: white;
        }

        .no-match {
            text-align: center;
            font-size: 1.2rem;
            color: #ff1a75;
            margin-top: 30px;
        }

        .card img {
            height: 250px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <h1>Your Matching Pets</h1>

    <div class="row">
        <?php if(count($matches) > 0): ?>
            <?php foreach($matches as $pet): ?>
                <div class="col-md-4">
                    <div class="card">
                        <?php if($pet['photo']): ?>
                            <img src="assets/images/<?= $pet['photo'] ?>" alt="<?= htmlspecialchars($pet['name']) ?>">
                        <?php else: ?>
                            <img src="assets/images/default_pet.jpg" alt="Default Pet">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pet['name']) ?></h5>
                            <p class="card-text">
                                <strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?><br>
                                <strong>Size:</strong> <?= htmlspecialchars($pet['size']) ?><br>
                                <strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?><br>
                                <strong>Temperament:</strong> <?= htmlspecialchars($pet['temperament']) ?><br>
                                <strong>Activity Level:</strong> <?= htmlspecialchars($pet['activity_level']) ?><br>
                                <strong>Shelter Location:</strong> <?= htmlspecialchars($pet['shelter_location']) ?><br>
                                <strong>Background:</strong> <?= htmlspecialchars($pet['description']) ?>
                            </p>
                            <a href="adopt.php?pet_id=<?= $pet['id'] ?>" class="btn btn-success d-grid">Adopt</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-match">No pets matched your preferences. Try adjusting them!</p>
        <?php endif; ?>
    </div>

    <div class="text-center">
        <a href="match.php" class="btn btn-back">Back to Preferences</a>
    </div>
</div>
</body>
</html>
