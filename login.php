<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';
include('header.php');
// include('student_dashboard.php');
// include('faculty_dashboard.php');
// include('admin_dashboard.php');


if (isset($_SESSION['User_id'])) {
    if ($_SESSION['user_role'] == 'student') {
        header("Location: student_dashboard.php");
    } elseif ($_SESSION['User_id'] == 'faculty') {
        header("Location: faculty_dashboard.php");
    } elseif ($_SESSION['User_id'] == 'admin') {
        header("Location: admin_dashboard.php");
    }
} else { $_SESSION['User_id'] = null; }
// above code allows user to be taken to dashboard.php if already logged in :)


// allows the user to log in if refreshed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Role Verify
    if (empty($_POST["role"])) {
        $RoleErr = "Role selection is required";
    } else {
        $Role = test_input($_POST["role"]);
    }

    // ID Verify
    if (empty($_POST["id"])) {
        $IdErr = "ID is required";
    } else {
        $Id = test_input($_POST["id"]);

        // $sql = "SELECT id, password, name, status, email, role FROM user WHERE id = '$Id' AND role = '$Role'";
        // $sql = "SELECT User_id, password, role FROM `Users` WHERE User_id = '$Id' AND role = '$Role'";
        $sql = "SELECT u.User_id, u.password, u.role
        FROM Users u
        -- JOIN student s ON u.User_id = s.user_id
        -- JOIN faculty f ON u.User_id = f.user_id
        -- JOIN admin a ON u.User_id = a.user_id
        WHERE u.User_id = '$Id' AND u.role = '$Role'";

        $result = mysqli_query($connection, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $User = mysqli_fetch_assoc($result); // allocating query results to `$User` variable (php variable)

            if ($User['User_id'] != $Id) {
                $IdErr = "ID is not Matched";
            }
        } else {
            $IdErr = "ID is not Matched";
        }
    }

    // Password Verify
    if (empty($_POST["password"])) {
        $PasswordErr = "Password is required";
    } else {
        $Password = test_input($_POST["password"]);

        if (isset($User['User_id'])) {
            if ($User['password'] != $Password) {
                $PasswordErr = "Password is not Matched";
            } else {
                // Successful login
                $_SESSION['User_id'] = $User['User_id'];
                $_SESSION['user_role'] = $User['role'];

                // Redirect based on the role
                if ($User['role'] == 'student') {
                    $studentName = $User['name'];
                    header("Location: student_dashboard.php");
                } elseif ($User['role'] == 'faculty') {
                    $facultyName = $User['faculty_name'];
                    header("Location: faculty_dashboard.php");
                } elseif ($User['role'] == 'admin') {
                    $adminName = $User['admin_name'];
                    header("Location: admin_dashboard.php");
                }
            }
        } else {
            $PasswordErr = "Password is not Matched";
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center my-4">Login</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="needs-validation" novalidate>
                    <!-- Role selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">Choose a role</option>
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo isset($RoleErr) ? $RoleErr : 'Please choose a role'; ?>
                        </div>
                    </div>

                    <!-- ID input -->
                    <div class="mb-3">
                        <label for="id" class="form-label">ID:</label>
                        <input type="text" id="id" name="id" class="form-control" placeholder="Enter your ID" required>
                        <div class="invalid-feedback">
                            <?php echo isset($IdErr) ? $IdErr : 'Please enter your ID'; ?>
                        </div>
                    </div>

                    <!-- Password input -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                        <div class="invalid-feedback">
                            <?php echo isset($PasswordErr) ? $PasswordErr : 'Please enter your password'; ?>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <!-- the submit action submits the credentials to the server to verify -->
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })
    </script>
</body>
</html>
