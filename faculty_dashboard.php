<?php
session_start();
// Include your database connection file
include('includes/db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'faculty') {
    header("Location: login.php");
}
$user_id = $_SESSION['User_id'];

// Fetch faculty ID and name
$facultyQuery = "SELECT f.faculty_id, f.faculty_name FROM faculty AS f JOIN Users AS u ON f.user_id = u.User_id WHERE u.User_id = ?";
$stmt = $connection->prepare($facultyQuery);
$stmt->bind_param('i', $user_id);
if ($stmt->execute()) {
    $stmt->bind_result($facultyId, $facultyName);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error fetching faculty information: " . $stmt->error;
}

$_SESSION['faculty_id'] = $facultyId;

// Fetch department information
$departmentQuery = "SELECT department.department_name, faculty.joining_year FROM faculty JOIN department ON faculty.department_id = department.department_id WHERE faculty.faculty_id = ?"; // ? is for binding variable to prepared statement as parameters with bind_param()
$stmt = $connection->prepare($departmentQuery);
$stmt->bind_param('i', $facultyId);
if ($stmt->execute()) {
    $stmt->bind_result($departmentName, $startYear);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error fetching department information: " . $stmt->error;

}



// fetching the current year
$currentYear = date("Y");

$semesters = ['sem1', 'sem2'];

    if(isset($_GET['submit'])){
        $year = $_GET['year'];
        $semester = $_GET['sem'];
        header("Location:semester_courses.php?year={$year}&sem={$semester}");

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Faculty Dashboard</h2>
        <p>Department: <?= $departmentName ?></p>

        <!-- <form method="GET" action="evaluation_results.php"> IGNORED-->
        <form action="semester_courses.php">

            <div class="mb-3">
                <label for="year" class="form-label">Select Year:</label>
                <select id="year" name="year" class="form-select">
                    <?php
                    // Use a for loop to iterate through the years and create an option element for each
                    for ($year = $startYear; $year <= $currentYear; $year++) {
                        echo "<option value='{$year}'>{$year}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="sem" class="form-label">Select Semester:</label>
                <select id="sem" name="sem" class="form-select">
                    <?php
                    foreach ($semesters as $semester) {
                        echo "<option value='{$semester}'>{$semester}</option>";
                    }
                    ?>
                </select>
            </div>



            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>

        </form>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script></body>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    </body>
    </html>

<!-- this page fetches department of tbhe faculty and and facutly_id and name.
fetching of courses (based on year and sem is done in semester_courses.php) -->
