<?php
session_start();
include('db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get the student ID from the query parameter
$student_id = $_POST['id']; // GK: POST is used when the data is submitted from using the 
// HTML form using HTTP POST method

// Retrieve the student data from the database
$query = "SELECT * FROM student WHERE id = $student_id";
$result = mysqli_query($connection, $query);
$student = mysqli_fetch_assoc($result);

$query_courses = "SELECT * FROM courses";
$result_courses = mysqli_query($connection, $query_courses);

// Update the student data if the form is submitted
if (isset($_POST['submit'])) {
    $id = $_POST['student_id'];
    $name = $_POST['name'];
    $department = $_POST['department_name'];
    $section = $_POST['section'];
    $admission_year = $_POST['admission_year'];
    $semester = $_POST['semester'];

    $query = "UPDATE student SET name='$name', department='$department', section='$section', admission_year='$admission_year', semester='$semester' WHERE id='$id'";
    $result = mysqli_query($connection, $query);

    $course_id = $_POST['course'];

    // Check if the student is already enrolled in the selected course
    $query_enrollment = "SELECT * FROM enrollment WHERE student_id = $id AND course_id = $course_id";
    $result_enrollment = mysqli_query($connection, $query_enrollment);

    if (mysqli_num_rows($result_enrollment) == 0) {
        // If the student is not enrolled, insert a new record into the enrollment table
        $query_insert_enrollment = "INSERT INTO enrollment (student_id, course_id) VALUES ($id, $course_id)";
        $result_insert_enrollment = mysqli_query($connection, $query_insert_enrollment);

        if (!$result_insert_enrollment) {
            echo "Error updating enrollment record: " . mysqli_error($connection);
        }
    }

    if ($result) {
        // Redirect to the student info page if the update was successful
        header("Location: student_info.php");
        exit;
    } else {
        // Display an error message if the update failed
        echo "Error updating student record: " . mysqli_error($connection);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Edit Student Info</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $student['name']; ?>">
            </div>
            <div class="mb-3">
                <label for="department_name" class="form-label">department</label>
                <input type="text" class="form-control" id="department_name" name="department_name" value="<?php echo $student['department_name']; ?>">
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">section</label>
                <input type="text" class="form-control" id="section" name="section" value="<?php echo $student['section']; ?>">
            </div>
            <div class="mb-3">
                <label for="admission_year" class="form-label">admission year</label>
                <input type="number" class="form-control" id="admission_year" name="admission_year" value="<?php echo $student['admission_year']; ?>">
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="text" class="form-control" id="semester" name="semester" value="<?php echo $student['semester']; ?>">
            </div>
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select class="form-control" id="course" name="course">
                    <?php while ($course = mysqli_fetch_assoc($result_courses)) { ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <input type="hidden" name="id" value="<?php echo $student_id; ?>">
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <!-- Bootstrap and JavaScript -->
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
