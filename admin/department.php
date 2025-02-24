<?php
include "../includes/config.php";
include "../includes/session.php";


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle AJAX Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'add') {
        $name = $_POST['name'];
        $query = "INSERT INTO department (name) VALUES ('$name')";
        echo mysqli_query($conn, $query) ? 'success' : 'error';
        exit();
    }
    
    if ($action == 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $query = "UPDATE department SET name='$name' WHERE id='$id'";
        echo mysqli_query($conn, $query) ? 'success' : 'error';
        exit();
    }
    
    if ($action == 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM department WHERE id='$id'";
        echo mysqli_query($conn, $query) ? 'success' : 'error';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "sidebar.php";?>
    <div class="container p-4">
        <h2>Manage Departments</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">Add Department</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="departmentTable">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM department");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr id='row{$row['id']}'>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm editDeptBtn' data-id='{$row['id']}' data-name='{$row['name']}' data-bs-toggle='modal' data-bs-target='#editDepartmentModal'>Edit</button>
                                <button class='btn btn-danger btn-sm deleteDeptBtn' data-id='{$row['id']}'>Delete</button>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addDepartmentForm">
                        <div class="mb-3">
                            <label class="form-label">Department Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#addDepartmentForm').submit(function (e) {
                e.preventDefault();
                $.post('department.php', $(this).serialize(), function (response) {
                    if (response === 'success') location.reload();
                });
            });

            $('.editDeptBtn').click(function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                $('#editDeptId').val(id);
                $('#editDeptName').val(name);
            });

            $('.deleteDeptBtn').click(function () {
                let id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.post('department.php', { id: id, action: 'delete' }, function (response) {
                        if (response === 'success') $('#row' + id).remove();
                    });
                }
            });
        });
    </script>
</body>
</html>
