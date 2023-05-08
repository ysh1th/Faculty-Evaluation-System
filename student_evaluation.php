<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
}

$studentId = $_SESSION['student_id'];
$facultyId = $_GET['faculty_id'];
$courseId = $_GET['course_id'];
$courseName = $_GET['course_name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    $criteria1 = $_POST['criteria1'];
    $criteria2 = $_POST['criteria2'];
    $criteria3 = $_POST['criteria3'];
    
    $query = "UPDATE enrollment
    SET evaluated = 'evaluated'
    WHERE student_id = $studentId AND course_id = $courseId;";
    $result = mysqli_query($connection, $query);
    
    $insertEvaluation = "INSERT INTO faculty_eval (student_id, faculty_id, course_id, criteria_rate1, criteria_rate2, criteria_rate3, year, sem) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insertEvaluation);

    mysqli_stmt_bind_param($stmt, 'iiiiiiss', $studentId, $facultyId, $courseId, $criteria1, $criteria2, $criteria3, $year, $semester);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: student_dashboard.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
}
// used prepared statements for anti-sql injection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Evaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Evaluate Faculty</h1>
        <form method="post">
            
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" class="form-control" id="year" name="year" min="2020" max="<?php echo date('Y'); ?>" required>
            </div>

            <div class="form-group">
                <label for="semester">Semester:</label>
                <select class="form-control" id="semester" name="semester" required>
                    <option value="sem1">Semester 1</option>
                    <option value="sem2">Semester 2</option>
                </select>
            </div>

            <div class="form-group">
                <label for="criteria1">Criteria 1 (Max 5):</label>
                <input type="number" class="form-control" id="criteria1" name="criteria1" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label for="criteria2">Criteria 2 (Max 5):</label>
                <input type="number" class="form-control" id="criteria2" name="criteria2" min="1" max="5" required>
            </div>

            <div class="form-group">
                <label for="criteria3">Criteria 3 (Max 5):</label>
                <input type="number" class="form-control" id="criteria3" name="criteria3" min="1" max="5" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Evaluation</button>
            <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>

        </form>
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


<!-- CREATE TABLE faculty_evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    faculty_id INT,
    criteria1_rating INT,
    criteria2_rating INT,
    criteria3_rating INT,
    total_score INT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
); -->
