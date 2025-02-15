<?php
include "../includes/session.php";
include "../includes/config.php";

$employee_id = $_SESSION['employee_id'];
$date = date("Y-m-d");
$punch_status = ""; // To store the punch in/out status

// Handle Punch In
if (isset($_POST['punch_in'])) {
    $punch_in_time = date("Y-m-d H:i:s");

    // Check if already punched in today
    $check_query = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND DATE(punch_in) = '$date'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows == 0) {
        $sql = "INSERT INTO attendance (employee_id, punch_in) VALUES ('$employee_id', '$punch_in_time')";
        if ($conn->query($sql) === TRUE) {
            $punch_status = "punch_in_success"; // ✅ Punch In Successful
        } else {
            $punch_status = "punch_in_error"; // ❌ Punch In Failed
        }
    } else {
        $punch_status = "already_punched_in"; // ⚠️ Already Punched In
    }
}

// Handle Punch Out
if (isset($_POST['punch_out'])) {
    $punch_out_time = date("Y-m-d H:i:s");

    // Check if already punched out
    $check_query = "SELECT * FROM attendance WHERE employee_id = '$employee_id' AND DATE(punch_in) = '$date' AND punch_out IS NULL";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $sql = "UPDATE attendance SET punch_out = '$punch_out_time', 
                work_hours = TIMESTAMPDIFF(SECOND, punch_in, '$punch_out_time') / 3600
                WHERE employee_id = '$employee_id' AND DATE(punch_in) = '$date'";

        if ($conn->query($sql) === TRUE) {
            $punch_status = "punch_out_success"; // ✅ Punch Out Successful
        } else {
            $punch_status = "punch_out_error"; // ❌ Punch Out Failed
        }
    } else {
        $punch_status = "not_punched_in"; // ⚠️ Not Punched In or Already Punched Out
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Punch In/Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/punch.css" rel="stylesheet">
    <style>
        .modal-content {
    border-radius: 15px;
    text-align: center;
}

.modal-body i {
    font-size: 60px;
}

.modal-footer {
    border-top: none;
}

.btn-success, .btn-danger {
    font-size: 18px;
    padding: 12px 20px;
    border-radius: 8px;
    margin: 10px;
}

.container {
    max-width: 500px;
    margin: auto;
    padding-top: 50px;
}

    </style>
</head>
<body>

    <!-- Bootstrap Success/Error Modal -->
    <div class="modal fade" id="punchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <?php if ($punch_status == "punch_in_success"): ?>
                        <i class="bi bi-check-circle text-success display-4"></i>
                        <h4 class="mt-2">Punch In Successful!</h4>
                    <?php elseif ($punch_status == "punch_in_error"): ?>
                        <i class="bi bi-x-circle text-danger display-4"></i>
                        <h4 class="mt-2">Punch In Failed!</h4>
                    <?php elseif ($punch_status == "already_punched_in"): ?>
                        <i class="bi bi-exclamation-circle text-warning display-4"></i>
                        <h4 class="mt-2">You have already punched in today!</h4>
                    <?php elseif ($punch_status == "punch_out_success"): ?>
                        <i class="bi bi-check-circle text-success display-4"></i>
                        <h4 class="mt-2">Punch Out Successful!</h4>
                    <?php elseif ($punch_status == "punch_out_error"): ?>
                        <i class="bi bi-x-circle text-danger display-4"></i>
                        <h4 class="mt-2">Punch Out Failed!</h4>
                    <?php elseif ($punch_status == "not_punched_in"): ?>
                        <i class="bi bi-exclamation-circle text-warning display-4"></i>
                        <h4 class="mt-2">You have not punched in today or already punched out!</h4>
                    <?php endif; ?>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (!empty($punch_status)) : ?>
                var myModal = new bootstrap.Modal(document.getElementById('punchModal'));
                myModal.show();
                setTimeout(() => { window.location.href = 'punch.php'; }, 2000); // Auto-close and redirect
            <?php endif; ?>
        });
    </script>
</body>
</html>
