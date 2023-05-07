<?php
session_start();
include('db.php');

$facultyId = $_SESSION['faculty_id'];
$year = $_GET['year'];
$selectedSemester = $_GET['sem'];

// $selectedSemester = intval($_GET['sem']);

// fetching courses taught by the faculty in selected sem
$coursesQuery = "SELECT c.course_id, c.course_name, s.section FROM course c JOIN sections s ON c.id = s.course_id WHERE s.faculty_id = $facultyId AND c.sem = $selectedSemester";
// update the above query, section table is not made as per above query
$coursesResult = mysqli_query($connection, $coursesQuery);
$courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2 class="text-center my-4">Semester Courses</h2>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Course Name</th>
                    <th scope="col">Section</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['course_name'] ?></td>
                        <td><?= $course['section_number'] ?></td>
                        <td><a href="evaluation_results.php?course_id=<?= $course['id'] ?>&year={$year}&sem={$selectedSemester}" class="btn btn-primary">View Results</a></td>
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
