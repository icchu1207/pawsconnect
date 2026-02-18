<?php
include "../config.php";

$success = '';

if(isset($_POST['submit'])){
    $name = $_POST['reporter_name'];
    $email = $_POST['reporter_email'] ?? '';
    $phone = $_POST['reporter_phone'] ?? '';
    $location = $_POST['location'];
    $description = $_POST['description'] ?? '';

    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['name'] != ''){
        $photo = time() . '_' . basename($_FILES['photo']['name']);
        $targetDir = "../upload/"; 
        if(!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetDir . $photo);
    }


    $stmt = $conn->prepare("INSERT INTO stray_reports (reporter_name, reporter_email, reporter_phone, location, description, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $location, $description, $photo);
    $stmt->execute();
    $stmt->close();

    $success = "Stray report submitted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report a Stray Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        label {
            font-weight: bold;
            color: #ff1a75;
        }

        .btn-warning {
            background: linear-gradient(45deg, #ff66b3, #ff1a75);
            border: none;
            font-weight: bold;
        }

        .btn-warning:hover {
            background: linear-gradient(45deg, #ff1a75, #ff66b3);
            color: white;
        }

        .alert-success {
            background: linear-gradient(45deg, #ffb3d9, #ff66b3);
            color: white;
            border: none;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <h1>Report a Stray Animal</h1>

    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Your Name</label>
                    <input type="text" name="reporter_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email (Optional)</label>
                    <input type="email" name="reporter_email" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Phone (Optional)</label>
                    <input type="text" name="reporter_phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe the animal and situation"></textarea>
                </div>

                <div class="mb-3">
                    <label>Photo (Optional)</label>
                    <input type="file" name="photo" class="form-control">
                </div>

                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-warning btn-lg">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
