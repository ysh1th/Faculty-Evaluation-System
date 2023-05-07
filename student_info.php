<?php
session_start();
include('db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$query = "SELECT s.student_id, s.name, d.department_name, s.section, s.admission_year, s.semester
FROM students AS s
JOIN department AS d ON s.department_id = d.department_id;";
// change the query, few attributes are changed in the database
//done
$result = mysqli_query($connection, $query);
$students_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Info</title>
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
        <h2 class="text-center my-4">Student Information</h2>
        <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th>Admission year</th>
                    <th>current sem</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students_data as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                    <td><?php echo htmlspecialchars($student['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['section']); ?></td>
                    <td><?php echo htmlspecialchars($student['admission_year']); ?></td>
                    <td><?php echo htmlspecialchars($student['semester']); ?></td>
                    <td>

                        <form method="post" action="edit_student.php">
                            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Edit</button>
                        </form> 
                     <!-- student id is getting passed as parameter to edit_student.php -->
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
