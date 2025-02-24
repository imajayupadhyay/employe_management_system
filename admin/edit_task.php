<?php
include "../includes/config.php";
include "../includes/session.php";

if (!isset($_GET['id'])) {
    die("Task ID is missing!");
}

$task_id = $_GET['id'];

// Fetch task details
$task_query = "SELECT * FROM assigned_tasks WHERE id = '$task_id'";
$task_result = $conn->query($task_query);

if ($task_result->num_rows == 0) {
    die("Task not found.");
}

$task = $task_result->fetch_assoc();

// Handle Task Update
$update_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_description = $conn->real_escape_string($_POST['task_description']);
    $deadline = $conn->real_escape_string($_POST['deadline']);
    $status = $conn->real_escape_string($_POST['status']);

    $update_query = "UPDATE assigned_tasks SET 
                        task_description = '$task_description', 
                        deadline = '$deadline',
                        status = '$status'
                     WHERE id = '$task_id'";

    if ($conn->query($update_query) === TRUE) {
        $update_status = "success";
    } else {
        $update_status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/mrwvrvsk4a9x9n68ecjzxtrvr3nkhcuqrxfuju1ad32ya65v/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#task_description',
            height: 350,
            menubar: false,
            plugins: 'lists',
            toolbar: 'undo redo | bold italic | bullist numlist'
        });
    </script>
    <style>
        .modal-content {
            border-radius: 15px;
            text-align: center;
        }
        .btn-primary {
            background: #007bff;
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>
<body>


    <div class="container mt-5">
   
        <h2 class="text-center">Edit Task</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Task Description:</label>
                <textarea id="task_description" name="task_description"><?= $task['task_description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label>Deadline:</label>
                <input type="date" name="deadline" value="<?= $task['deadline']; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?= ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Processing" <?= ($task['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
                    <option value="Completed" <?= ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Rejected" <?= ($task['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
            <a href="manage_tasks.php" class="btn btn-secondary">Cancel</a>
        </form>
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
                setTimeout(() => { window.location.href = 'manage_tasks.php'; }, 2000);
            <?php endif; ?>
        });
    </script>
</body>
</html>
