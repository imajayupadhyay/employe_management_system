<?php
include "../includes/session.php";
include "../includes/config.php";

$employee_id = $_SESSION['employee_id'];

// Fetch tasks assigned to the logged-in user
$tasks_query = "SELECT at.*, e.first_name, e.last_name 
                FROM assigned_tasks at
                JOIN employees e ON at.assigned_by = e.id
                WHERE assigned_to = '$employee_id'
                ORDER BY assigned_at DESC";
$tasks_result = $conn->query($tasks_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assigned Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/tasks.css" rel="stylesheet">
    <style>
     .navbar{
    background-color:#05386b;
   margin:0px;
   border-radius:25px;
}
.navbar-brand{
    color:white;
    font-size:bold;
}
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <nav class="navbar px-3 mb-3">
                <a class="navbar-brand p-2">Your Assigned Tasks</a>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Assigned By</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $tasks_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $task['first_name'] . " " . $task['last_name'] ?></td>
                            <td><?= $task['task_description'] ?></td>
                            <td><?= $task['status'] ?></td>
                            <td>
                                <a href="update_task.php?task_id=<?= $task['id'] ?>" class="btn btn-sm btn-warning">Update</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
