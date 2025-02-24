<?php
include "../includes/config.php";

if (isset($_POST['department_id'])) {
    $department_id = (int)$_POST['department_id'];
    $query = "SELECT * FROM designation WHERE department_id = $department_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
        }
    } else {
        echo '<option value="">No Designations Found</option>';
    }
}
?>
