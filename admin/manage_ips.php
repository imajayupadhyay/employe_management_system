<?php
include "../includes/config.php";
include "../includes/session.php";

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all stored IPs
$ip_query = "SELECT * FROM ip_restrictions";
$ip_result = $conn->query($ip_query);

// Fetch all employees with their restriction status
$employees_query = "SELECT e.id, e.first_name, e.last_name, 
                    (SELECT COUNT(*) FROM ip_restrictions WHERE ip_restrictions.ip_restriction_enabled = 1) AS ip_restriction 
                    FROM employees e";
$employees_result = $conn->query($employees_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage IP Restrictions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .card { box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        .btn { border-radius: 8px; font-weight: bold; }
        .checkbox-lg { transform: scale(1.3); margin-right: 8px; }
    </style>
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="container pt-5">

    <h3 class="text-center pt-5">Manage IP Restrictions</h3>

    <!-- Add IP Form -->
    <div class="card p-4 mt-3">
        <h5>Add New IP</h5>
        <form id="addIpForm">
            <input type="text" name="ip_address" id="ip_address" class="form-control mb-2" placeholder="Enter IP Address" required>
            <button type="submit" class="btn btn-primary">Add IP</button>
        </form>
    </div>

    <!-- List of IPs with Edit/Delete -->
    <div class="card p-4 mt-3">
        <h5>List of Allowed IPs</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>IP Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ipList">
                <?php while ($row = $ip_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['ip_address'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-ip" data-id="<?= $row['id'] ?>" data-ip="<?= $row['ip_address'] ?>">Edit</button>
                            <button class="btn btn-danger btn-sm delete-ip" data-id="<?= $row['id'] ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<!-- Employee List with IP Restriction Checkbox -->
<div class="card p-4 mt-3">
    <h5>Enable IP Restriction for Employees</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Restrict by IP?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch employees and check if they are restricted
            $employees_query = "SELECT e.id, e.first_name, e.last_name, 
                                COALESCE(r.restricted, 0) AS is_restricted
                                FROM employees e
                                LEFT JOIN restricted_users r ON e.id = r.employee_id";
            $employees_result = $conn->query($employees_query);

            while ($emp = $employees_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $emp['first_name'] . " " . $emp['last_name'] ?></td>
                    <td>
                        <input type="checkbox" class="checkbox-lg toggle-ip-restriction"
                            data-id="<?= $emp['id'] ?>" <?= $emp['is_restricted'] ? "checked" : "" ?>>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<!-- jQuery & AJAX Scripts -->
<script>
$(document).ready(function () {
    // Add IP
    $("#addIpForm").submit(function (e) {
        e.preventDefault();
        var ip = $("#ip_address").val();
        $.post("ajax_manage_ip.php", { action: "add_ip", ip_address: ip }, function (response) {
            alert(response);
            location.reload();
        });
    });

    // Edit IP
    $(".edit-ip").click(function () {
        var id = $(this).data("id");
        var new_ip = prompt("Enter new IP Address:", $(this).data("ip"));
        if (new_ip) {
            $.post("ajax_manage_ip.php", { action: "edit_ip", id: id, ip_address: new_ip }, function (response) {
                alert(response);
                location.reload();
            });
        }
    });

    // Delete IP
    $(".delete-ip").click(function () {
        if (confirm("Are you sure you want to delete this IP?")) {
            var id = $(this).data("id");
            $.post("ajax_manage_ip.php", { action: "delete_ip", id: id }, function (response) {
                alert(response);
                location.reload();
            });
        }
    });

// Toggle IP Restriction for Employees
$(".toggle-ip-restriction").change(function () {
    var employee_id = $(this).data("id");
    var enabled = $(this).is(":checked") ? 1 : 0;

    $.post("ajax_manage_ip.php", {
        action: "toggle_ip_restriction",
        employee_id: employee_id,
        enabled: enabled
    }, function (response) {
        alert(response);
    });
});

});
</script>

</body>
</html>