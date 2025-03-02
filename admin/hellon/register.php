<?php
include "../../includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure Password Hashing

    $sql = "INSERT INTO admin (first_name, last_name, email, password) 
            VALUES ('$first_name', '$last_name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Admin Registered Successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <style>
        @import url("admin.css");

.register-box {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    width: 450px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
}

input.form-control {
    height: 45px;
    font-size: 16px;
}

    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="form-box shadow-lg">
            <h3 class="text-center">Admin Registration</h3>
            <form method="POST">
                <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
                <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="text-center mt-2">Already registered? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
