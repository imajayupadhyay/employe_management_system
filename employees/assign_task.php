<?php
include "../includes/session.php";
include "../includes/config.php";

// Fetch all employees except the logged-in user
$employee_id = $_SESSION['employee_id'];
$employees_query = "SELECT id, first_name, last_name FROM employees WHERE id != '$employee_id'";
$employees_result = $conn->query($employees_query);

// Handle Task Assignment
$task_status = ""; // Variable to track task assignment status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assigned_to = $_POST['assigned_to'];
    $task_description = $conn->real_escape_string($_POST['task_description']);
    $deadline = $_POST['deadline']; // Get deadline from form input

    $sql = "INSERT INTO assigned_tasks (assigned_by, assigned_to, task_description, deadline)
            VALUES ('$employee_id', '$assigned_to', '$task_description', '$deadline')";

    if ($conn->query($sql) === TRUE) {
        $task_status = "success"; // ✅ Task Assigned Successfully
    } else {
        $task_status = "error"; // ❌ Failed to Assign Task
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/tasks.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #05386b;
            margin: 0px;
            border-radius: 25px;
        }
        .navbar-brand {
            color: white;
            font-weight: bold;
        }
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

    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.cloud/1/mrwvrvsk4a9x9n68ecjzxtrvr3nkhcuqrxfuju1ad32ya65v/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#task_description',
            height: 350,
            menubar: false,
            plugins: 'lists',
            toolbar: 'undo redo | bold italic | bullist numlist',
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <nav class="navbar px-3 mb-3">
                <a class="navbar-brand p-2">Assign a Task</a>
            </nav>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Employee</label>
                    <select name="assigned_to" class="form-control" required>
                        <?php while ($row = $employees_result->fetch_assoc()) { ?>
                            <option value="<?= $row['id'] ?>"><?= $row['first_name'] . " " . $row['last_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Task Description</label>
                    <textarea id="task_description" name="task_description"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-30">Assign Task</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Success/Error Modal -->
    <div class="modal fade" id="taskAssignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <?php if ($task_status == "success"): ?>
                        <i class="bi bi-check-circle text-success display-4"></i>
                        <h4 class="mt-2">Task Assigned Successfully!</h4>
                    <?php elseif ($task_status == "error"): ?>
                        <i class="bi bi-x-circle text-danger display-4"></i>
                        <h4 class="mt-2">Failed to Assign Task</h4>
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
            <?php if ($task_status == "success" || $task_status == "error") : ?>
                var myModal = new bootstrap.Modal(document.getElementById('taskAssignModal'));
                myModal.show();
                setTimeout(() => { window.location.href = 'assign_task.php'; }, 2000); // Auto-close and redirect
            <?php endif; ?>
        });
    </script>
</body>
</html>
