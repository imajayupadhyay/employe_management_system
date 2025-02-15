<?php
include "../includes/config.php";
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch All Employees
$employees = $conn->query("SELECT id, first_name, last_name FROM employees");

// Check if Employee is Selected
$selected_employee = isset($_GET['employee_id']) ? $conn->real_escape_string($_GET['employee_id']) : '';
$attendance_records = [];

if (!empty($selected_employee)) {
    // Debug: Check Employee Selection
    if (!is_numeric($selected_employee)) {
        die("Invalid Employee ID");
    }

    // Fetch Attendance Records
    $attendance_query = "SELECT * FROM attendance WHERE employee_id = '$selected_employee' ORDER BY date DESC";
    $attendance_result = $conn->query($attendance_query);

    if (!$attendance_result) {
        die("Error: " . $conn->error); // Debugging - Show SQL error if query fails
    }

    while ($row = $attendance_result->fetch_assoc()) {
        $attendance_records[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Attendance Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/attendance.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <?php include "sidebar.php"; ?>
        
        <div class="main-content">
            <h2 class="mb-4">Attendance Report</h2>

            <!-- Employee Selection Form -->
            <form method="GET" class="mb-3">
                <label for="employee">Select Employee:</label>
                <select name="employee_id" id="employee" class="form-control w-50 d-inline-block" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($row = $employees->fetch_assoc()) { ?>
                        <option value="<?= $row['id']; ?>" <?= ($selected_employee == $row['id']) ? 'selected' : ''; ?>>
                            <?= $row['first_name'] . " " . $row['last_name']; ?>
                        </option>
                    <?php } ?>
                </select>
                <button type="submit" class="btn btn-primary">View Report</button>
            </form>

            <!-- Attendance Table -->
            <?php if (!empty($selected_employee)): ?>
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
                        <?php if (!empty($attendance_records)): ?>
                            <?php foreach ($attendance_records as $record): ?>
                                <tr>
                                    <td><?= date("d M Y", strtotime($record['date'])); ?></td>
                                    <td><?= date("h:i A", strtotime($record['punch_in'])); ?></td>
                                    <td><?= ($record['punch_out']) ? date("h:i A", strtotime($record['punch_out'])) : 'Not Yet'; ?></td>
                                    <td><?= ($record['worked_hours']) ? $record['worked_hours'] . ' hrs' : 'Pending'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No attendance records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
