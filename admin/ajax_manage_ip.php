<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../includes/config.php";
session_start();

// Ensure only admins can access
if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized access!";
    exit();
}

// Handle AJAX Requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // ✅ Add IP Address
    if ($action == "add_ip") {
        $ip_address = $conn->real_escape_string($_POST['ip_address']);

        // Check if IP already exists
        $check_ip = "SELECT * FROM ip_restrictions WHERE ip_address = '$ip_address'";
        $check_result = $conn->query($check_ip);
        
        if ($check_result->num_rows > 0) {
            echo "⚠️ This IP is already in the system!";
        } else {
            $insert_query = "INSERT INTO ip_restrictions (ip_address) VALUES ('$ip_address')";
            if ($conn->query($insert_query) === TRUE) {
                echo "✅ IP Address added successfully!";
            } else {
                echo "❌ Error adding IP: " . $conn->error;
            }
        }
    }

    // ✅ Edit IP Address
    if ($action == "edit_ip") {
        $id = intval($_POST['id']);
        $ip_address = $conn->real_escape_string($_POST['ip_address']);

        $update_query = "UPDATE ip_restrictions SET ip_address = '$ip_address' WHERE id = $id";
        if ($conn->query($update_query) === TRUE) {
            echo "✅ IP Address updated successfully!";
        } else {
            echo "❌ Error updating IP: " . $conn->error;
        }
    }

    // ✅ Delete IP Address
    if ($action == "delete_ip") {
        $id = intval($_POST['id']);
        $delete_query = "DELETE FROM ip_restrictions WHERE id = $id";

        if ($conn->query($delete_query) === TRUE) {
            echo "✅ IP Address deleted!";
        } else {
            echo "❌ Error deleting IP: " . $conn->error;
        }
    }

    // ✅ Toggle IP Restriction for Employees
    if ($_POST['action'] == "toggle_ip_restriction") {
        $employee_id = $conn->real_escape_string($_POST['employee_id']);
        $enabled = $conn->real_escape_string($_POST['enabled']);
    
        if ($enabled == 1) {
            // Add employee to restricted_users table
            $sql = "INSERT INTO restricted_users (employee_id, restricted) VALUES ('$employee_id', 1)
                    ON DUPLICATE KEY UPDATE restricted = 1";
        } else {
            // Remove employee from restricted_users table
            $sql = "DELETE FROM restricted_users WHERE employee_id = '$employee_id'";
        }
    
        if ($conn->query($sql) === TRUE) {
            echo "✔ Restriction updated successfully!";
        } else {
            echo "❌ Error updating restriction: " . $conn->error;
        }
        exit();
    }    
}
?>
