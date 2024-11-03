<?php
session_start();
include("connection.php");

// Redirect if not logged in or if role is not 'instructor'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'instructor') {
    header("location: login.php");
    exit();
}

// Retrieve instructor_id from session
$instructor_id = $_SESSION['instructor_id'] ?? null;

if (!$instructor_id) {
    echo "<div class='alert alert-danger'>Instructor ID is missing. Please log in again.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);

    // Insert student with associated instructor_id
    $query = "INSERT INTO students (first_name, last_name, email, gender, year, section_id, course_id, instructor_id) 
              VALUES ('$first_name', '$last_name', '$email', '$gender', '$year', '$section_id', '$course_id', '$instructor_id')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Student added successfully!";
        header("Location: add_student.php");
        exit();
    } else {
        $_SESSION['message'] = "Error adding student: " . mysqli_error($conn);
        header("Location: add_student.php");
        exit();
    }
}

// Retrieve sections and courses for dropdowns
$sections = mysqli_query($conn, "SELECT * FROM sections");
$courses = mysqli_query($conn, "SELECT * FROM courses");

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            color: #2B547E;
        }

        .alert {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #2B547E;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1e3d5d;
        }

        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Instructor Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="container mt-5">
        <div class="card mt-3">
            <div class="card-body">
                <h1>Add Student</h1>
                <h4 class="mb-4">Instructor: <?php echo htmlspecialchars($_SESSION['username']); ?></h4>

                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year</label>
                        <select type="text" class="form-select" id="year" name="year" required>
                            <option value="">Select Year</option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                            <option value="4th">4th</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_id" class="form-label">Section</label>
                        <select class="form-select" id="section_id" name="section_id" required>
                            <option value="">Select Section</option>
                            <?php while ($row = mysqli_fetch_assoc($sections)): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['section_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php while ($row = mysqli_fetch_assoc($courses)): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
            <br>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>