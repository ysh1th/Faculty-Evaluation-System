<?php
session_start();
include('db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = "SELECT f.faculty_id, f.faculty_name, d.department FROM faculty";
$result = mysqli_query($connection, $query);
$faculty_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Faculty Information</h2>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faculty_data as $faculty): ?>
                <tr>
                    <td><?php echo htmlspecialchars($faculty['faculty_id']); ?></td>
                    <td><?php echo htmlspecialchars($faculty['faculty_name']); ?></td>
                    <td><?php echo htmlspecialchars($faculty['department']); ?></td>
                    <td>

                        <form method="post" action="edit_faculty.php">
                            <input type="hidden" name="id" value="<?php echo $faculty['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Edit</button>
                        </form>
                        
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
