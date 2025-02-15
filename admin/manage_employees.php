<?php
include "../includes/config.php";
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Employee Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id = $id");
    header("Location: manage_employees.php");
}

// Fetch All Employees
$employees = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <style>
        .main-content{
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="main-content">
            <h2>Manage Employees</h2>
            <a href="add_employee.php" class="btn btn-success mb-3">+ Add Employee</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $employees->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td>
                                <a href="edit_employee.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="manage_employees.php?delete=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
