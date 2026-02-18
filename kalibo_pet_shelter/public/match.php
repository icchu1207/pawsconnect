<!DOCTYPE html>
<html>
<head>
    <title>Find a Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffe6f0, #ffcce6);
            min-height: 100vh;
        }

        h1 {
            color: #ff1a75;
            text-shadow: 1px 1px 3px #ff66b3;
        }

        label {
            font-weight: bold;
            color: #ff1a75;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .btn-gradient {
            background: linear-gradient(45deg, #ff66b3, #ff1a75);
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #ff1a75, #ff66b3);
            color: white;
        }

        input, select {
            border-radius: 10px;
        }

        .form-container {
            background: #fff0f6;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <h1 class="text-center mb-4">Tell Us Your Preferences</h1>

    <div class="form-container mx-auto" style="max-width: 600px;">
        <form action="results.php" method="post">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Preferred Breed</label>
                <input type="text" name="preferred_breed" class="form-control">
            </div>

            <div class="mb-3">
                <label>Preferred Size</label>
                <select name="preferred_size" class="form-control">
                    <option value="Small">Small</option>
                    <option value="Medium">Medium</option>
                    <option value="Large">Large</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Preferred Age</label>
                <input type="number" name="preferred_age" class="form-control">
            </div>

            <div class="mb-3">
                <label>Preferred Behavior</label>
                <input type="text" name="preferred_behavior" class="form-control">
            </div>

            <div class="mb-3">
                <label>Preferred Temperament</label>
                <input type="text" name="preferred_temperament" class="form-control">
            </div>

            <div class="mb-3">
                <label>Preferred Activity Level</label>
                <select name="preferred_activity_level" class="form-control">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-gradient btn-lg">Find Matches</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
