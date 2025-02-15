<?php
include "../includes/config.php";  // ✅ Add this to include database connection
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total employees
$employee_query = "SELECT COUNT(*) AS total FROM employees";
$employee_result = $conn->query($employee_query);
$total_employees = $employee_result->fetch_assoc()['total'] ?? 0;

// Fetch total tasks
$task_query = "SELECT COUNT(*) AS total FROM assigned_tasks";
$task_result = $conn->query($task_query);
$total_tasks = $task_result->fetch_assoc()['total'] ?? 0;

// Fetch total attendance records
$attendance_query = "SELECT COUNT(*) AS total FROM attendance";
$attendance_result = $conn->query($attendance_query);
$total_attendance = $attendance_result->fetch_assoc()['total'] ?? 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

body {
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff; /* White Background */
    color: #333;
}

.wrapper {
    display: flex;
    height: 100vh;
}

.main-content {
    margin-left: 250px; /* Space for Sidebar */
    width: 100%;
    padding: 20px;
    background: #ffffff; /* White Background */
}

h1, h2, h3, h4, h5, h6, p {
    font-weight: 400;
    font-style: normal;
}
.main-content {
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.card h5 {
    font-size: 18px;
    color: #6c757d;
}

.card h3 {
    font-size: 28px;
    font-weight: bold;
    color: #007bff;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        <div class="main-content">
            <h2 class="mb-4">Welcome, <?= $_SESSION['admin_name']; ?>!</h2>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <h5>Total Employees</h5>
                        <h3><?= $total_employees ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <h5>Total Tasks</h5>
                        <h3><?= $total_tasks ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <h5>Total Attendance Records</h5>
                        <h3><?= $total_attendance ?></h3>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <h5>Attendance Overview</h5>
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <h5>Task Progress</h5>
                        <canvas id="taskChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/admin_charts.js"></script>

    <script>
        loadAttendanceChart(<?= $total_attendance ?>);
        loadTaskChart(<?= $total_tasks ?>);
    </script>
</body>
</html>
