<?php
include __DIR__ . "/../config.php"; 
$type = $_GET['type'] ?? '';
$size = $_GET['size'] ?? '';
$gender = $_GET['gender'] ?? '';
$temperament = $_GET['temperament'] ?? '';

$sql = "SELECT * FROM pets WHERE 1=1";

if ($type !== '') {
    $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
}
if ($size !== '') {
    $sql .= " AND size = '" . $conn->real_escape_string($size) . "'";
}
if ($gender !== '') {
    $sql .= " AND gender = '" . $conn->real_escape_string($gender) . "'";
}
if ($temperament !== '') {
    $sql .= " AND temperament = '" . $conn->real_escape_string($temperament) . "'";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PawsConnect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            padding: 20px;
            margin: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .header img.logo {
            max-height: 180px;
            width: auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .header .btn-left,
        .header .btn-right {
            padding: 12px 20px;
            border-radius: 8px;
            background: #ff5c9d;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }

        .header .btn-left:hover,
        .header .btn-right:hover {
            background: #e91e63;
        }

        .header .btn-left {
            order: 1;
        }

        .header .btn-right {
            order: 2;
        }

        .filters {
            background: rgba(255,255,255,0.9);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filters select,
        .filters button,
        .filters a {
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-weight: bold;
        }

        .filters button {
            background: #ff5c9d;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .filters button:hover {
            background: #e91e63;
        }

        .filters a {
            background: #ddd;
            text-decoration: none;
            color: #333;
        }
        .pet-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .pet-card {
            background: #fff;
            width: 270px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            overflow: hidden;
            text-align: center;
            padding-bottom: 15px;
        }

        .pet-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .pet-card h2 {
            color: #ff5c9d;
            margin: 12px 0 6px;
        }

        .primary-info {
            font-weight: bold;
            color: #444;
            margin-bottom: 6px;
        }

        .pet-card p {
            margin: 4px 0;
            font-size: 14px;
        }

        .adopt-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 18px;
            background: #ff5c9d;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .no-pets {
            text-align: center;
            color: #fff;
            font-size: 20px;
            margin-top: 50px;
        }

        @media (max-width: 800px) {
            .header img.logo {
                max-height: 140px;
            }
        }

    </style>
</head>
<body>

<div class="header">
    <a href="stray_report.php" class="btn-left">Stray Report</a>
    <img src="assets/images/logo.png" alt="PawsConnect Logo" class="logo">
    <a href="admin_login.php" class="btn-right">Log In</a>
</div>

<form class="filters" method="GET">
    <select name="type">
        <option value="">All Types</option>
        <option value="Dog" <?= $type=="Dog" ? "selected" : "" ?>>Dog</option>
        <option value="Cat" <?= $type=="Cat" ? "selected" : "" ?>>Cat</option>
        <option value="Bird" <?= $type=="Bird" ? "selected" : "" ?>>Bird</option>
    </select>

    <select name="size">
        <option value="">All Sizes</option>
        <option value="Small" <?= $size=="Small" ? "selected" : "" ?>>Small</option>
        <option value="Medium" <?= $size=="Medium" ? "selected" : "" ?>>Medium</option>
        <option value="Large" <?= $size=="Large" ? "selected" : "" ?>>Large</option>
    </select>

    <select name="gender">
        <option value="">All Genders</option>
        <option value="Male" <?= $gender=="Male" ? "selected" : "" ?>>Male</option>
        <option value="Female" <?= $gender=="Female" ? "selected" : "" ?>>Female</option>
    </select>

    <select name="temperament">
        <option value="">All Temperaments</option>
        <option value="Friendly" <?= $temperament=="Friendly" ? "selected" : "" ?>>Friendly</option>
        <option value="Calm" <?= $temperament=="Calm" ? "selected" : "" ?>>Calm</option>
        <option value="Playful" <?= $temperament=="Playful" ? "selected" : "" ?>>Playful</option>
        <option value="Shy" <?= $temperament=="Shy" ? "selected" : "" ?>>Shy</option>
    </select>

    <button type="submit">Search 🔍</button>
    <a href="index.php">Reset</a>
</form>

<div class="pet-cards">
<?php
if ($result && $result->num_rows > 0) {
    while ($pet = $result->fetch_assoc()) {
?>
    <div class="pet-card">
        <img src="../upload/<?= htmlspecialchars($pet['image']) ?>" alt="<?= htmlspecialchars($pet['name']) ?>">

        <h2><?= htmlspecialchars($pet['name']) ?></h2>

        <div class="primary-info">
            <?= htmlspecialchars($pet['type']) ?> • <?= htmlspecialchars($pet['size']) ?> • <?= htmlspecialchars($pet['gender']) ?>
        </div>

        <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?></p>
        <p><strong>Temperament:</strong> <?= htmlspecialchars($pet['temperament']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($pet['location']) ?></p>

        <a class="adopt-btn" href="adopt.php?pet_id=<?= $pet['id'] ?>">
            Adopt Me 🐾
        </a>
    </div>
<?php
    }
} else {
    echo '<div class="no-pets">No pets match your search.</div>';
}
?>
</div>

</body>
</html>
