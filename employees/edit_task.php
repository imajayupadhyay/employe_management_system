<?php
include "../includes/session.php";
include "../includes/config.php";

if (!isset($_GET['task_id'])) {
    die("Task ID is missing!");
}

$task_id = $_GET['task_id'];
$employee_id = $_SESSION['employee_id'];

// Fetch task details
$task_query = "SELECT * FROM tasks WHERE id = '$task_id' AND employee_id = '$employee_id'";
$task_result = $conn->query($task_query);

if ($task_result->num_rows == 0) {
    die("Task not found or unauthorized access.");
}

$task = $task_result->fetch_assoc();
$task_date = date("Y-m-d", strtotime($task['task_date']));
$today_date = date("Y-m-d");

if ($task_date != $today_date) {
    die("You can only edit tasks on the same day.");
}

// Handle task update
$update_status = ""; // To store update status message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_description = $conn->real_escape_string($_POST['task_description']);

    $update_query = "UPDATE tasks SET task_description = '$task_description' WHERE id = '$task_id'";

    if ($conn->query($update_query) === TRUE) {
        $update_status = "success"; // ✅ Task Updated Successfully
    } else {
        $update_status = "error"; // ❌ Failed to Update Task
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Task</title>
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
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Task</h2>

        <form method="POST">
            <textarea id="task_description" name="task_description"><?= $task['task_description']; ?></textarea>
            <button type="submit" class="btn btn-primary mt-3">Update Task</button>
            <a href="tasks.php" class="btn btn-secondary mt-3">Cancel</a>
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
                setTimeout(() => { window.location.href = 'tasks.php'; }, 2000); // Auto-close and redirect
            <?php endif; ?>
        });
    </script>
</body>
</html>
