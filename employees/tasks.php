<?php
include "../includes/session.php";
include "../includes/config.php";

$employee_id = $_SESSION['employee_id'];
$today_date = date("Y-m-d");

// Fetch tasks for this employee
$task_query = "SELECT * FROM tasks WHERE employee_id = '$employee_id' ORDER BY created_at DESC";
$task_result = $conn->query($task_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/tasks.css" rel="stylesheet">
    
    <!-- TinyMCE Editor -->
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
        .navbar {
            background-color: #05386b;
            margin: 0px;
            border-radius: 25px;
        }
        .navbar-brand {
            color: white;
            font-weight: bold;
        }
        @import url("global.css");


/* Task Form */
#task_description {
    width: 100%;
    height: 350px;
}

/* Task Container */
.task-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Task Card */
.task-card {
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.task-card .card-header {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px 8px 0 0;
    font-size: 18px;
    font-weight: bold;
}

.task-card .card-body {
    padding: 10px;
    font-size: 16px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <nav class="navbar px-3 mb-3">
                <a class="navbar-brand p-2">Your Tasks</a>
            </nav>
            
            <!-- Task Input Form -->
            <form method="POST" action="add_task.php" onsubmit="updateEditorContent()">
    <textarea id="task_description" name="task_description"></textarea>
    <button type="submit" class="btn btn-primary w-30 mt-2">Add Task</button>
</form>


            <!-- Task Display -->
            <div class="task-container mt-4 ">
                <?php while ($task = $task_result->fetch_assoc()) { 
                    $task_date = date("Y-m-d", strtotime($task['task_date']));
                    $is_today = ($task_date == $today_date);
                ?>
                    <div class="card task-card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <span><strong><?= date("M d, Y", strtotime($task['task_date'])); ?></strong></span>
                            <?php if ($is_today) { ?>
                                <a href="edit_task.php?task_id=<?= $task['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php } ?>
                        </div>
                        <div class="card-body">
                            <?= $task['task_description']; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
