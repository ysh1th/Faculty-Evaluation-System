<?php
session_start();
include('db.php');

$facultyId = $_SESSION['faculty_id'];
$year = $_GET['year'];
$selectedSemester = $_GET['sem'];
$courseId = $_GET['course_id'];

// Fetching evaluation scores for courses taught by the faculty in the selected semester and year
$evaluationsQuery = "SELECT c.course_name, e.criteria1, e.criteria2, e.criteria3 FROM course c JOIN sections s ON c.id = s.course_id JOIN evaluations e ON c.id = e.course_id WHERE s.faculty_id = $facultyId AND c.sem = $selectedSemester AND year = $year";
//change the above query
$evaluationsResult = mysqli_query($connection, $evaluationsQuery);
$results = mysqli_fetch_all($evaluationsResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Scores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <h2 class="text-center my-4">Evaluation Scores</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Course</th>
                <th>Criteria 1</th>
                <th>Criteria 2</th>
                <th>Criteria 3</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?= $result['course_name'] ?></td>
                    <td><?= $result['criteria1'] ?></td>
                    <td><?= $result['criteria2'] ?></td>
                    <td><?= $result['criteria3'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>

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
