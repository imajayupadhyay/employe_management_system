<?php
include "../includes/session.php";
include "../includes/config.php";

if (!isset($_GET['task_id'])) {
    die("Task ID is missing!");
}

$task_id = $_GET['task_id'];
$employee_id = $_SESSION['employee_id'];

// Fetch task details
$task_query = "SELECT * FROM assigned_tasks WHERE id = '$task_id' AND assigned_to = '$employee_id'";
$task_result = $conn->query($task_query);

if ($task_result->num_rows == 0) {
    die("Task not found or you are not authorized to update this task.");
}

$task = $task_result->fetch_assoc();

// Handle form submission
$update_status = ""; // To store update status message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $issue = $conn->real_escape_string($_POST['issue']);

    $update_query = "UPDATE assigned_tasks 
                     SET status = '$status', issue = '$issue', updated_at = NOW() 
                     WHERE id = '$task_id'";

    if ($conn->query($update_query) === TRUE) {
        $update_status = "success"; // ✅ Task Updated Successfully
    } else {
        $update_status = "error"; // ❌ Failed to Update
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/tasks.css" rel="stylesheet">
    <style>
        .modal-content {
    border-radius: 15px;
    text-align: center;
}

.modal-body i {
    font-size: 60px;
}

.modal-footer {
    border-top: none;
}

.btn-primary {
    background: #007bff;
    border-radius: 8px;
    padding: 10px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <h2 class="text-center mt-3">Update Task</h2>

            <form method="POST" class="task-form">
                <div class="mb-3">
                    <label class="form-label">Task Description</label>
                    <textarea class="form-control" disabled><?= $task['task_description'] ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $task['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="Rejected" <?= $task['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mention Any Issue (Optional)</label>
                    <textarea name="issue" class="form-control"><?= $task['issue'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Task</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Success/Error Modal -->
    <div class="modal fade" id="taskUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <?php if ($update_status == "success"): ?>
                        <i class="bi bi-check-circle text-success display-4"></i>
                        <h4 class="mt-2">Task Updated Successfully!</h4>
                    <?php elseif ($update_status == "error"): ?>
                        <i class="bi bi-x-circle text-danger display-4"></i>
                        <h4 class="mt-2">Failed to Update Task</h4>
                    <?php endif; ?>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($update_status == "success" || $update_status == "error") : ?>
                var myModal = new bootstrap.Modal(document.getElementById('taskUpdateModal'));
                myModal.show();
                setTimeout(() => { window.location.href = 'assigned_tasks.php'; }, 2000); // Auto-close and redirect
            <?php endif; ?>
        });
    </script>
</body>
</html>
