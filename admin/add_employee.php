<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

include "../includes/config.php";
include "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and escape input values
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
    $middle_name = isset($_POST['middle_name']) ? $conn->real_escape_string($_POST['middle_name']) : '';
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $zip_code = isset($_POST['zip_code']) ? $conn->real_escape_string($_POST['zip_code']) : '';
    $contact_number = isset($_POST['contact_number']) ? $conn->real_escape_string($_POST['contact_number']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $employee_contract = isset($_POST['employee_contract']) ? $conn->real_escape_string($_POST['employee_contract']) : 'Full Time';
    $shift = isset($_POST['shift']) ? $conn->real_escape_string($_POST['shift']) : 'Morning Shift';
    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 1;
    $designation_id = isset($_POST['designation_id']) ? (int)$_POST['designation_id'] : 1;
    $date_of_joining = isset($_POST['date_of_joining']) ? $conn->real_escape_string($_POST['date_of_joining']) : date('Y-m-d');
    $date_of_birth = isset($_POST['date_of_birth']) ? $conn->real_escape_string($_POST['date_of_birth']) : date('Y-m-d', strtotime('-25 years'));
    $gender = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : 'Male';
    $marital_status = isset($_POST['marital_status']) ? $conn->real_escape_string($_POST['marital_status']) : 'Single';
    $pay_scale = isset($_POST['pay_scale']) ? $conn->real_escape_string($_POST['pay_scale']) : 'Negotiable';
    $emergency_contact = isset($_POST['emergency_contact']) ? $conn->real_escape_string($_POST['emergency_contact']) : '';
    $work_location = isset($_POST['work_location']) ? $conn->real_escape_string($_POST['work_location']) : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';

    // Insert query
    $sql = "INSERT INTO employees 
            (first_name, middle_name, last_name, address, zip_code, contact_number, email, employee_contract, shift, department_id, designation_id, date_of_joining, date_of_birth, gender, marital_status, pay_scale, emergency_contact, work_location, password) 
            VALUES 
            ('$first_name', '$middle_name', '$last_name', '$address', '$zip_code', '$contact_number', '$email', '$employee_contract', '$shift', '$department_id', '$designation_id', '$date_of_joining', '$date_of_birth', '$gender', '$marital_status', '$pay_scale', '$emergency_contact', '$work_location', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Employee Registered Successfully!'); window.location.href='manage_employees.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch departments and designations
$departments = $conn->query("SELECT * FROM department");
$designations = $conn->query("SELECT * FROM designation");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4"><span class="text-primary">Employee</span> Registration</h3>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4"><label>First Name</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-4"><label>Middle Name</label><input type="text" name="middle_name" class="form-control"></div>
                    <div class="col-md-4"><label>Last Name</label><input type="text" name="last_name" class="form-control" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label>Address</label><input type="text" name="address" class="form-control" required></div>
                    <div class="col-md-4"><label>ZIP Code</label><input type="text" name="zip_code" class="form-control" required></div>
                    <div class="col-md-4"><label>Contact Number</label><input type="text" name="contact_number" class="form-control" required></div>
                   

                </div>
                <div class="row mt-3">
                <div class="col-md-4">
    <label>Email Address</label>
    <input type="email" name="email" class="form-control" required>
</div>
                    <div class="col-md-4"><label>Date of Birth</label><input type="date" name="date_of_birth" class="form-control" required></div>
                    <div class="col-md-4"><label>Date of Joining</label><input type="date" name="date_of_joining" class="form-control" required></div>
                    
                </div>
                <div class="row mt-3">
                <div class="col-md-4"><label>Gender</label><select name="gender" class="form-control"><option value="Male">Male</option><option value="Female">Female</option><option value="Trans">Trans</option></select></div>
                    <div class="col-md-4"><label>Marital Status</label><select name="marital_status" class="form-control"><option value="Single">Single</option><option value="Married">Married</option><option value="Divorced">Divorced</option><option value="Widowed">Widowed</option></select></div>
                    <div class="col-md-4"><label>Pay Scale</label><input type="text" name="pay_scale" class="form-control" required></div>
                    <div class="col-md-4 mt-3"><label>Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" required></div>
                    <div class="col-md-4 mt-3"><label>Work Location</label><input type="text" name="work_location" class="form-control" required></div>
                    <div class="col-md-4 mt-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Department</label>
                        <select name="department_id" class="form-control" required>
                            <?php while ($dept = $departments->fetch_assoc()) { ?>
                                <option value="<?= $dept['id']; ?>"><?= $dept['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Designation</label>
                        <select name="designation_id" class="form-control" required>
                            <?php while ($desig = $designations->fetch_assoc()) { ?>
                                <option value="<?= $desig['id']; ?>"><?= $desig['title']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Register Employee</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
