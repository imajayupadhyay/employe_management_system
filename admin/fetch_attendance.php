<?php
include "../includes/config.php";

// Check if request is via AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Get filter values
$employee_id = isset($_POST['employee_id']) ? $conn->real_escape_string($_POST['employee_id']) : '';
$month = isset($_POST['month']) ? $conn->real_escape_string($_POST['month']) : '';

// Base query
$query = "SELECT a.*, e.first_name, e.last_name 
          FROM attendance a
          JOIN employees e ON a.employee_id = e.id
          WHERE 1";

// Apply Employee Filter
if (!empty($employee_id) && is_numeric($employee_id)) {
    $query .= " AND a.employee_id = '$employee_id'";
}

// Apply Month Filter
if (!empty($month)) {
    $query .= " AND DATE_FORMAT(a.punch_in, '%Y-%m') = '$month'";
}

$query .= " ORDER BY a.punch_in DESC";
$result = $conn->query($query);

// Debug: Check for SQL errors
if (!$result) {
    die("SQL Error: " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "<h4>Attendance Records</h4>
          <table class='table table-bordered mt-3'>
          <thead>
              <tr>
                  <th>Date</th>
                  <th>Punch In</th>
                  <th>Punch Out</th>
                  <th>Worked Hours</th>
              </tr>
          </thead>
          <tbody>
              <tr><td colspan='4' class='text-center'>No records found.</td></tr>
          </tbody>
          </table>";
    exit();
}

// Generate the table dynamically
echo "<h4>Attendance Records</h4>
      <table class='table table-bordered mt-3'>
      <thead>
          <tr>
              <th>Date</th>
              <th>Punch In</th>
              <th>Punch Out</th>
              <th>Worked Hours</th>
          </tr>
      </thead>
      <tbody>";

while ($record = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . date("d M Y", strtotime($record['punch_in'])) . "</td>
            <td>" . date("h:i A", strtotime($record['punch_in'])) . "</td>
            <td>" . (!empty($record['punch_out']) ? date("h:i A", strtotime($record['punch_out'])) : 'Not Yet') . "</td>
            <td>" . (!empty($record['work_hours']) ? number_format($record['work_hours'], 2) . " hrs" : "Pending") . "</td>
          </tr>";
}

echo "</tbody></table>";
?>
