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
        $title = $_POST['title'];
        $department_id = $_POST['department_id'];
        $query = "INSERT INTO designation (title, department_id) VALUES ('$title', '$department_id')";
        echo mysqli_query($conn, $query) ? 'success' : 'error';
        exit();
    }
    
    if ($action == 'edit') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $department_id = $_POST['department_id'];
        $query = "UPDATE designation SET title='$title', department_id='$department_id' WHERE id='$id'";
        echo mysqli_query($conn, $query) ? 'success' : 'error';
        exit();
    }
    
    if ($action == 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM designation WHERE id='$id'";
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
    <title>Manage Designations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "sidebar.php";?>
    <div class="container p-4">
        
        <h2>Manage Designations</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDesignationModal">Add Designation</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="designationTable">
                <?php
                $result = mysqli_query($conn, "SELECT d.id, d.title, dep.name as department FROM designation d JOIN department dep ON d.department_id = dep.id");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr id='row{$row['id']}'>
                            <td>{$row['id']}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['department']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm editDesigBtn' data-id='{$row['id']}' data-title='{$row['title']}' data-dept='{$row['department']}' data-bs-toggle='modal' data-bs-target='#editDesignationModal'>Edit</button>
                                <button class='btn btn-danger btn-sm deleteDesigBtn' data-id='{$row['id']}'>Delete</button>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Designation Modal -->
    <div class="modal fade" id="addDesignationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addDesignationForm">
                        <div class="mb-3">
                            <label class="form-label">Designation Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-control" required>
                                <?php
                                $departments = mysqli_query($conn, "SELECT * FROM department");
                                while ($dept = mysqli_fetch_assoc($departments)) {
                                    echo "<option value='{$dept['id']}'>{$dept['name']}</option>";
                                }
                                ?>
                            </select>
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
            $('#addDesignationForm').submit(function (e) {
                e.preventDefault();
                $.post('designation.php', $(this).serialize(), function (response) {
                    if (response === 'success') location.reload();
                });
            });

            $('.editDesigBtn').click(function () {
                let id = $(this).data('id');
                let title = $(this).data('title');
                let dept = $(this).data('dept');
                $('#editDesigId').val(id);
                $('#editDesigTitle').val(title);
                $('#editDesigDept').val(dept);
            });

            $('.deleteDesigBtn').click(function () {
                let id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.post('designation.php', { id: id, action: 'delete' }, function (response) {
                        if (response === 'success') $('#row' + id).remove();
                    });
                }
            });
        });
    </script>
</body>
</html>