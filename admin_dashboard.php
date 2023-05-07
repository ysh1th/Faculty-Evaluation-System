<?php
session_start();
include('db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['User_id'];
$adminId = "SELECT admin_id from admin AS a JOIN Users AS u ON a.user_id = u.User_id WHERE u.User_id = '$user_id'";
$adminName = "SELECT a.admin_name FROM admin AS a
JOIN Users AS u ON a.user_id = u.User_id
WHERE u.User_id = '$user_id'";

$_SESSION['admin_id'] = $admintId;
$_SESSION['admin_name'] = $adminName;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center my-4">Admin Dashboard</h2>
                <p class="text-center">Welcome, <?php echo htmlspecialchars($admin_name); ?>!</p>
                <div class="btn-container">
                    <a href="student_info.php" class="btn btn-primary">Student Info</a>
                    <a href="faculty_info.php" class="btn btn-primary">Faculty Info</a>
                </div>
            </div>
        </div>
        <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
