<?php
session_start();

// Check if the user is an admin or employee and redirect accordingly
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['employee_id'])) {
    // If neither an admin nor an employee is logged in, redirect them to the correct login page
    $current_file = basename($_SERVER['PHP_SELF']);

    if (strpos($current_file, 'admin') !== false) {
        header("Location: /ems/admin/login.php"); // Redirect admins to admin login
    } else {
        header("Location: /ems/employees/login.php"); // Redirect employees to employee login
    }
    exit();
}
