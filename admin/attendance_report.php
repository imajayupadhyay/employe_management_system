<?php
include "../includes/config.php";
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all employees for the dropdown
$employees = $conn->query("SELECT id, first_name, last_name FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/attendance.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>

        <div class="main-content">
            <h2 class="mb-4">Attendance Report</h2>

            <!-- Filters for Employee and Month -->
            <form id="filterForm" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="employee">Select Employee:</label>
                        <select name="employee_id" id="employee" class="form-control">
                            <option value="">-- All Employees --</option>
                            <?php while ($row = $employees->fetch_assoc()) { ?>
                                <option value="<?= $row['id']; ?>">
                                    <?= $row['first_name'] . " " . $row['last_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="month">Select Month:</label>
                        <input type="month" name="month" id="month" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <!-- Attendance Table -->
            <div id="attendanceTable">
                <h4>Attendance Records</h4>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th>Worked Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-center">Select filters to view attendance.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#filterForm").submit(function (e) {
                e.preventDefault(); // Prevent page reload

                var employee_id = $("#employee").val();
                var month = $("#month").val();

                $.ajax({
                    url: "fetch_attendance.php",
                    type: "POST",
                    data: { employee_id: employee_id, month: month },
                    success: function (response) {
                        $("#attendanceTable").html(response);
                    },
                    error: function (xhr, status, error) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
