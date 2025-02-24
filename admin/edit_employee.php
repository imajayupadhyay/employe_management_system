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

if (!isset($_GET['id'])) {
    header("Location: manage_employees.php");
    exit();
}

$employee_id = $_GET['id'];
$result = $conn->query("SELECT * FROM employees WHERE id = $employee_id");
if ($result->num_rows == 0) {
    header("Location: manage_employees.php");
    exit();
}
$employee = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function cleanInput($conn, $key, $default = '') {
        return isset($_POST[$key]) ? $conn->real_escape_string($_POST[$key]) : $default;
    }

    $first_name = cleanInput($conn, 'first_name');
    $middle_name = cleanInput($conn, 'middle_name');
    $last_name = cleanInput($conn, 'last_name');
    $address = cleanInput($conn, 'address');
    $zip_code = cleanInput($conn, 'zip_code');
    $contact_number = cleanInput($conn, 'contact_number');
    $email = cleanInput($conn, 'email');

    // Validate ENUM fields to prevent truncation errors
    $employee_contract = cleanInput($conn, 'employee_contract', 'Full Time');
    $shift = cleanInput($conn, 'shift', 'Morning Shift');

    // Ensure IDs are integers
    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : 1;
    $designation_id = isset($_POST['designation_id']) ? (int)$_POST['designation_id'] : 1;

    // Ensure valid date values
    $date_of_joining = cleanInput($conn, 'date_of_joining', date('Y-m-d'));
    $date_of_birth = cleanInput($conn, 'date_of_birth', date('Y-m-d', strtotime('-25 years')));

    // Default values for other fields
    $gender = cleanInput($conn, 'gender', 'Male');
    $marital_status = cleanInput($conn, 'marital_status', 'Single');
    $pay_scale = cleanInput($conn, 'pay_scale', 'Negotiable');
    $emergency_contact = cleanInput($conn, 'emergency_contact', '');
    $work_location = cleanInput($conn, 'work_location', '');

    // Update query
    $update_query = "UPDATE employees SET 
                     first_name = '$first_name', 
                     middle_name = '$middle_name',
                     last_name = '$last_name',
                     address = '$address',
                     zip_code = '$zip_code',
                     contact_number = '$contact_number',
                     email = '$email',
                     employee_contract = '$employee_contract',
                     shift = '$shift',
                     department_id = '$department_id',
                     designation_id = '$designation_id',
                     date_of_joining = '$date_of_joining',
                     date_of_birth = '$date_of_birth',
                     gender = '$gender',
                     marital_status = '$marital_status',
                     pay_scale = '$pay_scale',
                     emergency_contact = '$emergency_contact',
                     work_location = '$work_location'
                     WHERE id = $employee_id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Employee Updated Successfully!'); window.location.href='manage_employees.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$departments = $conn->query("SELECT * FROM department");
$designations = $conn->query("SELECT * FROM designation WHERE department_id = {$employee['department_id']}");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4"><span class="text-primary">Edit</span> Employee</h3>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4"><label>First Name</label><input type="text" name="first_name" class="form-control" value="<?= $employee['first_name']; ?>" required></div>
                    <div class="col-md-4"><label>Middle Name</label><input type="text" name="middle_name" class="form-control" value="<?= $employee['middle_name']; ?>"></div>
                    <div class="col-md-4"><label>Last Name</label><input type="text" name="last_name" class="form-control" value="<?= $employee['last_name']; ?>" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label>Address</label><input type="text" name="address" class="form-control" value="<?= $employee['address']; ?>" required></div>
                    <div class="col-md-4"><label>ZIP Code</label><input type="text" name="zip_code" class="form-control" value="<?= $employee['zip_code']; ?>" required></div>
                    <div class="col-md-4"><label>Contact Number</label><input type="text" name="contact_number" class="form-control" value="<?= $employee['contact_number']; ?>" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label>Email Address</label><input type="email" name="email" class="form-control" value="<?= $employee['email']; ?>" required></div>
                    <div class="col-md-4"><label>Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="<?= $employee['date_of_birth']; ?>" required></div>
                    <div class="col-md-4"><label>Date of Joining</label><input type="date" name="date_of_joining" class="form-control" value="<?= $employee['date_of_joining']; ?>" required></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label>Department</label>
                        <select name="department_id" id="department" class="form-control" required>
                            <?php while ($dept = $departments->fetch_assoc()) { ?>
                                <option value="<?= $dept['id']; ?>" <?= $dept['id'] == $employee['department_id'] ? 'selected' : ''; ?>><?= $dept['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4"><label>Designation</label>
                        <select name="designation_id" id="designation" class="form-control" required>
                            <?php while ($desig = $designations->fetch_assoc()) { ?>
                                <option value="<?= $desig['id']; ?>" <?= $desig['id'] == $employee['designation_id'] ? 'selected' : ''; ?>><?= $desig['title']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4"><label>Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" value="<?= $employee['emergency_contact']; ?>" required></div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Update Employee</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#department').change(function() {
                var department_id = $(this).val();
                $.ajax({
                    url: 'fetch_designations.php',
                    type: 'POST',
                    data: { department_id: department_id },
                    success: function(response) {
                        $('#designation').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
