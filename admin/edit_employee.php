<?php
include "../includes/config.php";
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch Employee Details
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

// Handle Employee Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $middle_name = $conn->real_escape_string($_POST['middle_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $zip_code = $conn->real_escape_string($_POST['zip_code']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $employee_contract = $conn->real_escape_string($_POST['employee_contract']);
    $shift = $conn->real_escape_string($_POST['shift']);

    $update_query = "UPDATE employees SET 
                     first_name = '$first_name', 
                     middle_name = '$middle_name',
                     last_name = '$last_name',
                     address = '$address',
                     zip_code = '$zip_code',
                     contact_number = '$contact_number',
                     email = '$email',
                     employee_contract = '$employee_contract',
                     shift = '$shift'
                     WHERE id = $employee_id";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Employee Updated Successfully!'); window.location.href='manage_employees.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="../assets/css/register.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4"><span class="text-primary">Edit</span> Employee</h3>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?= $employee['first_name']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?= $employee['middle_name']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?= $employee['last_name']; ?>" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $employee['address']; ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label>ZIP Code</label>
                        <input type="text" name="zip_code" class="form-control" value="<?= $employee['zip_code']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="<?= $employee['contact_number']; ?>" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= $employee['email']; ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Employee Contract</label>
                        <select name="employee_contract" class="form-control">
                            <option value="Full Time" <?= $employee['employee_contract'] == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                            <option value="Part Time" <?= $employee['employee_contract'] == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Shift</label>
                        <select name="shift" class="form-control">
                            <option value="Morning Shift" <?= $employee['shift'] == 'Morning Shift' ? 'selected' : '' ?>>Morning Shift</option>
                            <option value="Night Shift" <?= $employee['shift'] == 'Night Shift' ? 'selected' : '' ?>>Night Shift</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3 w-100">Update Employee</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
