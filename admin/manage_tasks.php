<?php
include "../includes/config.php";
include "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Task Deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM assigned_tasks WHERE id = $id");
    header("Location: manage_tasks.php");
}

// Fetch All Assigned Tasks
$tasks = $conn->query("SELECT assigned_tasks.*, employees.first_name, employees.last_name FROM assigned_tasks 
                        JOIN employees ON assigned_tasks.assigned_to = employees.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <style>
        .main-content{
            background-color: white;
        }
        .table {
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.table th {
    background: #007bff;
    color: white;
    padding: 12px;
}

.table td {
    padding: 10px;
}

.status-completed {
    color: green;
    font-weight: bold;
}

.status-pending {
    color: orange;
    font-weight: bold;
}

.status-rejected {
    color: red;
    font-weight: bold;
}

.btn-success, .btn-warning, .btn-danger {
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 14px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="main-content">
            <h2>Manage Tasks</h2>
            <a href="assign_task.php" class="btn btn-success mb-3">+ Assign Task</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Task Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $tasks->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                            <td><?= $row['task_description']; ?></td>
                            <td><?= $row['status']; ?></td>
                            <td>
                                <a href="edit_task.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="manage_tasks.php?delete=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
