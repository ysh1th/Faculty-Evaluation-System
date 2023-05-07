<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
}
$user_id = $_SESSION['User_id'];
$studentId = "SELECT student_id from student AS s JOIN Users AS u ON s.user_id = u.User_id WHERE u.User_id = '$user_id'";
$studentName = "SELECT s.name FROM student AS s
JOIN Users AS u ON s.user_id = u.User_id
WHERE u.User_id = '$user_id'";

$_SESSION['student_id'] = $studentId;
$_SESSION['student_name'] = $studentName;


$semesterQuery = "SELECT semester FROM students WHERE id = '$studentId'";
$semesterResult = mysqli_query($connection, $semesterQuery);
$semesterData = mysqli_fetch_assoc($semesterResult);
$semester = $semesterData['semester'];



$coursesTakenQuery = "SELECT c.course_id, c.course_name, f.faculty_id, f.faculty_name, e.evaluated
FROM course c
JOIN course_faculty cf ON c.course_id = cf.course_id
JOIN faculty f ON cf.faculty_id = f.faculty_id
JOIN enrollment e ON c.course_id = e.course_id
JOIN student s ON e.student_id = s.student_id
WHERE s.section = (
  SELECT s.section
  FROM student s
  WHERE s.student_id = $studentId
) AND e.evaluated IN ('evaluated', 'not evaluated') AND e.student_id = '$studentId'";


$coursesTakenResult = mysqli_query($connection, $coursesTakenQuery);
$coursesTaken = mysqli_fetch_all($coursesTakenResult, MYSQLI_ASSOC);

$evaluatedCoursesCount = 0;
$totalCoursesTaken = count($coursesTaken);

foreach ($coursesTaken as $course) {
    if ($course['evaluated']) {
        $evaluatedCoursesCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <style>
        .card,
        .card-chart,
        .card-table {
            border-radius: 10px;
        }
        .card-chart {
            background: rgb(240, 240, 240);
        }
        .chart {
            background: rgb(230, 230, 230);
            border-radius: 5px;
        }
        .card-table {
            background: rgb(240, 240, 240);
        }
        tr:nth-child(even) {
            background-color: rgb(250, 250, 250);
        }
    </style> -->
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Welcome, <?php echo $studentName; ?></h1>
        <p>Student ID: <?php echo $studentId; ?></p>
        <p>Semester: <?php echo "Semester: {$semester}" ?></p>
        <h2 class="mt-3">Courses Evaluated</h2>
        <ul>
            <?php foreach ($coursesTaken as $course): ?>
                <?php if ($course['evaluated']): ?>
                    <li><?php echo $course['course_name']; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <h2 class="mt-3">Courses Taken</h2>
        <?php echo "Courses evaluated: {$evaluatedCoursesCount} / {$totalCoursesTaken}"; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Course id</th>
                    <th>Course name</th>
                    <th>Faculty Name</th>
                    <th>Evaluation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coursesTaken as $course): ?>
                    <tr>
                        <td><?php echo $course['course_id']; ?></td>
                        <td><?php echo $course['course_name']; ?></td>
                        <td><?php echo $course['faculty_name']; ?></td>
                        <td>
                            <?php if ($course['evaluated']): ?>
                                <button class="btn btn-secondary" disabled>Evaluated</button>
                            <?php else: ?>
                                <a href="student/student_evaluation.php?faculty_id=<?php echo $course['faculty_id']; ?>&course_name=<?php echo $course['course_name']; ?>&course_id=<?php echo $course['course_id']; ?>" class="btn btn-primary">Evaluate</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="login.php" class="btn btn-danger">Logout</a>
        <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
