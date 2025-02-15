<?php
include "../includes/session.php";
include "../includes/config.php";

$task_status = ""; // Variable to store task status message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_SESSION['employee_id'];
    $task_description = $conn->real_escape_string($_POST['task_description']);

    $sql = "INSERT INTO tasks (employee_id, task_description) VALUES ('$employee_id', '$task_description')";

    if ($conn->query($sql) === TRUE) {
        $task_status = "success"; // ✅ Task Added Successfully
    } else {
        $task_status = "error"; // ❌ Failed to Add Task
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Task</title>
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
    <div class="container">
        <!-- Bootstrap Success/Error Modal -->
        <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <?php if ($task_status == "success"): ?>
                            <i class="bi bi-check-circle text-success display-4"></i>
                            <h4 class="mt-2">Task Added Successfully!</h4>
                        <?php elseif ($task_status == "error"): ?>
                            <i class="bi bi-x-circle text-danger display-4"></i>
                            <h4 class="mt-2">Failed to Add Task</h4>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($task_status == "success" || $task_status == "error") : ?>
                var myModal = new bootstrap.Modal(document.getElementById('taskModal'));
                myModal.show();
                setTimeout(() => { window.location.href = 'tasks.php'; }, 2000); // Auto-close and redirect
            <?php endif; ?>
        });
    </script>
</body>
</html>
