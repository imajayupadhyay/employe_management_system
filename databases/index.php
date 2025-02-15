<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Employee Management System</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/index.css" rel="stylesheet">
    <style>

@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    text-align: center;
}

.container {
    max-width: 900px;
}

/* Role Box Styling */
.role-box {
    background: white;
    color: black;
    padding: 20px;
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
    display: block;
}

.role-box:hover {
    transform: translateY(-10px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
}

.role-avatar {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row text-center">
            <h2 class="mb-4">Welcome to Employee Management System</h2>

            <!-- Admin Container -->
            <div class="col-md-4">
                <a href="#" class="role-box">
                    <img src="../assets/images/admin.png" alt="Admin" class="role-avatar">
                    <h4>Admin</h4>
                </a>
            </div>

            <!-- Employee Container -->
            <div class="col-md-4">
                <a href="../employees/login.php" class="role-box">
                    <img src="../assets/images/division.png" alt="Employee" class="role-avatar">
                    <h4>Employee</h4>
                </a>
            </div>

            <!-- Manager Container -->
            <div class="col-md-4">
                <a href="#" class="role-box">
                    <img src="../assets/images/manager.png" alt="Manager" class="role-avatar">
                    <h4>Manager</h4>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
