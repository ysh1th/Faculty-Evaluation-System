<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['User_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Get the faculty ID from the query parameter
$faculty_id = $_GET['id'];

// Retrieve the faculty data from the database
$query = "SELECT * FROM faculty WHERE id = $faculty_id";
$result = mysqli_query($connection, $query);
$faculty = mysqli_fetch_assoc($result);

// Fetch the list of courses
$query = "SELECT * FROM course";
$result = mysqli_query($connection, $query);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch the courses the faculty is currently teaching
$query = "SELECT course_id FROM course-teaching WHERE faculty_id = $faculty_id";
$result = mysqli_query($connection, $query);
$faculty_courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
$faculty_courses_ids = array_column($faculty_courses, 'course_id');



// Update the faculty data if the form is submitted
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $title = $_POST['title'];

    $year = $_POST['year'];
    $sem = $_POST['sem'];
    $section = $_POST['section'];

    $query = "UPDATE faculty SET name='$name', department='$department' WHERE id='$id'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Update the courses the faculty is teaching
        $selected_courses = $_POST['courses'];

        // Remove the courses the faculty is no longer teaching
        $courses_to_remove = array_diff($faculty_courses_ids, $selected_courses);
        foreach ($courses_to_remove as $course_id) {
            $query = "DELETE FROM course_teaching WHERE faculty_id = $faculty_id AND course_id = $course_id";
            mysqli_query($connection, $query);
        }

        // Add the new courses the faculty is teaching
        $courses_to_add = array_diff($selected_courses, $faculty_courses_ids);
        foreach ($courses_to_add as $course_id) {
            $query = "INSERT INTO course_teaching (faculty_id, course_id, year, sem, section) VALUES ($faculty_id, $course_id, $year, '$sem', $section)";
            mysqli_query($connection, $query);
        }

        // Redirect to the faculty info page if the update was successful
        header("Location: faculty_info.php");
        exit;
    } else {
        echo "Error updating faculty record: " . mysqli_error($connection);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    pls fill in all the fields
    <div class="container">
        <h2 class="text-center my-4">Edit Faculty Info</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="faculty_id" class="form-label">Id</label>
                <input type="number" class="form-control" id="faculty_id" name="id" value="<?php echo $faculty['id']; ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $faculty['name']; ?>">
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" id="department" name="department" value="<?php echo $faculty['department']; ?>">
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Current Year</label>
                <input type="number" class="form-control" id="year" name="year" required>
            </div>

            <div class="mb-3">
                <label for="sem" class="form-label">Current Semester</label>
                <select class="form-control" id="sem" name="sem" required>
                    <option value="sem1">Semester 1</option>
                    <option value="sem2">Semester 2</option>
                </select>
            </div>

            <!-- <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <select class="form-control" id="section" name="section" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div> -->
            <div class="mb-3">
                <label for="courses" class="form-label">Courses</label>
                <select multiple class="form-control" id="courses" name="courses[]">
                    <?php foreach ($courses as $course): ?>
                        <optgroup label="<?php echo $course['course_name']; ?>">
                            <?php for ($section = 1; $section <= 3; $section++): ?>
                                <option value="<?php echo $course['course_id'] . '_section_' . $section; ?>" <?php echo in_array($course['course_id'] . '_section_' . $section, $faculty_courses_ids) ? 'selected' : ''; ?>><?php echo $course['course_name'] . ' - Section ' . $section; ?></option>
                            <?php endfor; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Update</button>
        </form>
        <button onclick="goBack()" class="btn btn-secondary mb-3">Go Back</button>
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
