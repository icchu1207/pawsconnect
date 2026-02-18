<?php
include "../config.php";

$pet = null;
$success = "";
$error = "";

if (isset($_GET['pet_id'])) {

    $pet_id = intval($_GET['pet_id']);

    $stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pet = $result->fetch_assoc();
    } else {
        die("Pet not found.");
    }

    $stmt->close();
} else {
    die("Invalid request.");
}

if (isset($_POST['submit'])) {

    $pet_id = intval($_POST['pet_id']);
    $fullname       = trim($_POST['fullname']);
    $age            = trim($_POST['age']);
    $address        = trim($_POST['address']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $occupation     = trim($_POST['occupation']);
    $employer       = trim($_POST['employer']);
    $length_address = trim($_POST['length_address']);

    $home_type      = $_POST['home_type'] ?? "";
    $num_people     = trim($_POST['num_people']);
    $children_info  = trim($_POST['children_info']);
    $has_pets       = $_POST['has_pets'] ?? "";
    $pets_info      = trim($_POST['pets_info']);
    $landlord_allow = $_POST['landlord_allow'] ?? "";

    $main_caregiver = trim($_POST['main_caregiver']);
    $reason         = trim($_POST['reason']);
    $hours_alone    = trim($_POST['hours_alone']);
    $stay_location  = trim($_POST['stay_location']);
    $training_plan  = trim($_POST['training_plan']);
    $travel_plan    = trim($_POST['travel_plan']);

    $monthly_budget = trim($_POST['monthly_budget']);
    $emergency_fund = $_POST['emergency_fund'] ?? "";
    $financial_proof = isset($_POST['financial_proof']) 
                        ? implode(", ", $_POST['financial_proof']) 
                        : "";
    $financial_file_path = ""; 
    $annual_vaccine = $_POST['annual_vaccine'] ?? "";
    $owned_before   = $_POST['owned_before'] ?? "";
    $what_happened  = trim($_POST['what_happened']);
    $surrendered    = trim($_POST['surrendered']);
    $long_term_resp = $_POST['long_term_resp'] ?? "";
    $date_signed = trim($_POST['date_signed']);
    $signature_path = "";

    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $upload_dir = "../uploads/signatures/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["signature"]["name"]);
        $target_file = $upload_dir . $file_name;
        move_uploaded_file($_FILES["signature"]["tmp_name"], $target_file);
        $signature_path = "uploads/signatures/" . $file_name;
    }

    if (isset($_FILES['financial_file']) && $_FILES['financial_file']['error'] == 0) {
        $upload_dir = "../uploads/financials/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["financial_file"]["name"]);
        $target_file = $upload_dir . $file_name;
        move_uploaded_file($_FILES["financial_file"]["tmp_name"], $target_file);
        $financial_file_path = "uploads/financials/" . $file_name;
    }

    $stmt = $conn->prepare("
        INSERT INTO adoptions (
            pet_id, fullname, age, address, phone, email,
            occupation, employer, length_address,
            home_type, num_people, children_info,
            has_pets, pets_info, landlord_allow,
            main_caregiver, reason, hours_alone,
            stay_location, training_plan, travel_plan,
            monthly_budget, emergency_fund, financial_proof,
            financial_file, annual_vaccine, owned_before, what_happened,
            surrendered, long_term_resp,
            signature, date_signed
        )
        VALUES (
            ?,?,?,?,?,?,
            ?,?,?,?,?,?,
            ?,?,?,?,?,?,
            ?,?,?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,?,?,
            ?,?
        )
    ");

    $stmt->bind_param(
        "issssssssssssssssssssssssssssssss",
        $pet_id, $fullname, $age, $address, $phone, $email,
        $occupation, $employer, $length_address,
        $home_type, $num_people, $children_info,
        $has_pets, $pets_info, $landlord_allow,
        $main_caregiver, $reason, $hours_alone,
        $stay_location, $training_plan, $travel_plan,
        $monthly_budget, $emergency_fund, $financial_proof,
        $financial_file_path, $annual_vaccine, $owned_before, $what_happened,
        $surrendered, $long_term_resp,
        $signature_path, $date_signed
    );

    if ($stmt->execute()) {
        $success = "Adoption application submitted successfully!";
    } else {
        $error = "Something went wrong.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Adoption Application</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: linear-gradient(135deg, #ffe6f0, #ffcce6); }
.card { border-radius: 15px; }
.section-title { color: #ff1a75; font-weight: bold; margin-top: 25px; }
.btn-gradient { background: linear-gradient(45deg, #ff66b3, #ff1a75); color: white; border: none; }
</style>
</head>
<body>
<div class="container mt-5 mb-5">

<h1 class="text-center mb-4">Adoption Application</h1>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
<input type="hidden" name="pet_id" value="<?= $pet['id'] ?>">

<div class="card p-4">

<h4 class="section-title">1. Applicant Information</h4>
<input class="form-control mb-2" name="fullname" placeholder="Full Name" required>
<input class="form-control mb-2" name="age" placeholder="Age" required>
<input class="form-control mb-2" name="address" placeholder="Address" required>
<input class="form-control mb-2" name="phone" placeholder="Phone Number" required>
<input class="form-control mb-2" name="email" type="email" placeholder="Email Address" required>
<input class="form-control mb-2" name="occupation" placeholder="Occupation">
<input class="form-control mb-2" name="employer" placeholder="Employer">
<input class="form-control mb-2" name="length_address" placeholder="How long at current address?">

<h4 class="section-title">2. Household Information</h4>
<select name="home_type" class="form-control mb-2">
<option value="">Type of Home</option>
<option>House</option>
<option>Rent</option>
<option>Apartment</option>
<option>Condo</option>
<option>Others</option>
</select>
<input class="form-control mb-2" name="num_people" placeholder="Number of people">
<textarea class="form-control mb-2" name="children_info" placeholder="Children ages"></textarea>
<select name="has_pets" class="form-control mb-2">
<option value="">Any pets?</option>
<option>Yes</option>
<option>No</option>
</select>
<textarea class="form-control mb-2" name="pets_info" placeholder="If yes, details"></textarea>
<select name="landlord_allow" class="form-control mb-2">
<option value="">Landlord allows pets?</option>
<option>Yes</option>
<option>No</option>
</select>

<h4 class="section-title">3. Pet Care Plan</h4>
<input class="form-control mb-2" name="main_caregiver" placeholder="Main caregiver">
<textarea class="form-control mb-2" name="reason" placeholder="Why adopt?"></textarea>
<input class="form-control mb-2" name="hours_alone" placeholder="Hours alone daily">
<input class="form-control mb-2" name="stay_location" placeholder="Where will pet stay?">
<textarea class="form-control mb-2" name="training_plan" placeholder="Training plan"></textarea>
<textarea class="form-control mb-2" name="travel_plan" placeholder="If you move/travel?"></textarea>

<h4 class="section-title">4. Financial Capability</h4>
<input class="form-control mb-2" name="monthly_budget" placeholder="Monthly budget">
<select name="emergency_fund" class="form-control mb-2">
<option value="">Emergency fund?</option>
<option>Yes</option>
<option>No</option>
</select>

<label>Proof of Financial Stability (select any)</label><br>
<input type="checkbox" name="financial_proof[]" value="Latest payslip"> Latest payslip<br>
<input type="checkbox" name="financial_proof[]" value="Certificate of employment"> Certificate of employment<br>
<input type="checkbox" name="financial_proof[]" value="Bank statement"> Bank statement<br>
<input type="checkbox" name="financial_proof[]" value="Sponsor Letter"> Sponsor Letter<br>

<label>Upload Financial Document</label>
<input type="file" name="financial_file" class="form-control mb-2">

<select name="annual_vaccine" class="form-control mt-2">
<option value="">Commit to annual vaccination?</option>
<option>Yes</option>
<option>No</option>
</select>

<h4 class="section-title">6. Experience</h4>
<select name="owned_before" class="form-control mb-2">
<option value="">Owned pets before?</option>
<option>Yes</option>
<option>No</option>
</select>
<textarea class="form-control mb-2" name="what_happened" placeholder="What happened to them?"></textarea>
<textarea class="form-control mb-2" name="surrendered" placeholder="Ever surrendered a pet? Why?"></textarea>
<select name="long_term_resp" class="form-control mb-2">
<option value="">Understand long-term responsibility?</option>
<option>Yes</option>
<option>No</option>
</select>

<h4 class="section-title">7. Agreement</h4>
<label>Upload Signature</label>
<input type="file" name="signature" class="form-control mb-2" required>
<input type="date" name="date_signed" class="form-control mb-3" required>

<div class="d-grid">
<button class="btn btn-gradient btn-lg" name="submit">Submit Application 🐾</button>
</div>

</div>
</form>

</div>
</body>
</html>
