<?php
include "../includes/config.php";
session_start();

// Get User IP Address
$user_ip = $_SERVER['REMOTE_ADDR'];

// Convert localhost IPv6 (::1) to IPv4 (127.0.0.1) for testing
if ($user_ip == "::1") {
    $user_ip = "127.0.0.1";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Fetch employee details
    $sql = "SELECT * FROM employees WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $employee_id = $row['id'];

        // Check if user is restricted
        $restricted_query = "SELECT * FROM restricted_users WHERE employee_id = '$employee_id' AND restricted = 1";
        $restricted_result = $conn->query($restricted_query);

        if ($restricted_result->num_rows > 0) {
            // User is restricted, now check if their IP is in the allowed list
            $ip_check_query = "SELECT * FROM ip_restrictions WHERE ip_address = '$user_ip'";
            $ip_check_result = $conn->query($ip_check_query);

            if ($ip_check_result->num_rows == 0) {
                // IP is not in the allowed list, deny login
                die("<h3 style='color:red; text-align:center;'>⚠️ Login denied: Your IP ($user_ip) is not authorized.</h3>");
            }
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['employee_id'] = $employee_id;
            $_SESSION['employee_name'] = $row['first_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            die("<h3 style='color:red; text-align:center;'>❌ Invalid credentials!</h3>");
        }
    } else {
        die("<h3 style='color:red; text-align:center;'>❌ No account found!</h3>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/login.css" rel="stylesheet">
    <style>
        @import url("global.css");

        /* Center Login Box */
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
            width: 400px;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        /* Slide-in Animation */
        .animate-login {
            transform: translateY(-50px);
            opacity: 0;
            animation: slideIn 0.8s ease forwards;
        }

        @keyframes slideIn {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Password Toggle Icon */
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            font-size: 18px;
            color: #6c757d;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #007bff;
        }

        /* Buttons */
        .btn-primary {
            background: #007bff;
            border-radius: 8px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-box shadow-lg animate-login">
            <div class="login-header">
                <a href="../index.php" class="back-arrow">&#8592;</a>
            </div>
            <h3 class="text-center fw-bold">Employee Log In</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter employee email" required>
                </div>
                <div class="mb-3 position-relative">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log In</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Password Visibility
        document.getElementById("togglePassword").addEventListener("click", function () {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.classList.remove("bi-eye-slash");
                this.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                this.classList.remove("bi-eye");
                this.classList.add("bi-eye-slash");
            }
        });
    </script>
</body>
</html>
