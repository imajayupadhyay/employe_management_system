<?php
include "../includes/session.php";
include "../includes/config.php";

$employee_id = $_SESSION['employee_id'];
$attendance_query = "SELECT * FROM attendance WHERE employee_id = '$employee_id' ORDER BY punch_in DESC";
$attendance_result = $conn->query($attendance_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/attendance.css" rel="stylesheet">
    <style>
     .navbar{
    background-color:#05386b;
   margin:0px;
   border-radius:25px;
}
.navbar-brand{
    color:white;
    font-size:bold;
}
@import url("global.css");

.table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

th {
    background: #007bff;
    color: white;
}

td {
    font-size: 16px;
}

h2{
    font-size:16px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <nav class="navbar px-3 mb-3">
                <a class="navbar-brand p-2">Attendance History</a>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Punch In</th>
                        <th>Punch Out</th>
                        <th>Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $attendance_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= date("M d, Y", strtotime($row['punch_in'])); ?></td>
                            <td><?= date("H:i:s", strtotime($row['punch_in'])); ?></td>
                            <td><?= $row['punch_out'] ? date("H:i:s", strtotime($row['punch_out'])) : '—'; ?></td>
                            <td><?= $row['work_hours'] ? $row['work_hours'] . ' hrs' : '—'; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
