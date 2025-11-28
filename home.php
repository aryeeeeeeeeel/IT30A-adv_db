<?php
session_start();
include("connection.php");

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit();
}

// Query to fetch student information with joined section and course data
$query_students = mysqli_query($conn, "
    SELECT s.id, s.first_name, s.last_name, s.email, s.gender, s.year, 
       COALESCE(sec.section_name, 'N/A') AS section_name, 
       COALESCE(c.course_name, 'N/A') AS course_name
    FROM students s
    LEFT JOIN sections sec ON s.section_id = sec.id  
    LEFT JOIN courses c ON s.course_id = c.id        
") or die("Error fetching student information: " . mysqli_error($conn));

// Query to fetch instructor information where role is 'instructor'
$query_instructors = mysqli_query($conn, "
    SELECT u.id, u.username, u.department, u.email
    FROM users u
    WHERE u.role = 'instructor'
") or die("Error fetching instructor information: " . mysqli_error($conn));

// Query to fetch all course data
$query_courses = mysqli_query($conn, "
    SELECT c.id, c.course_name, c.course_department, c.course_code
    FROM courses c
") or die("Error fetching course information: " . mysqli_error($conn));

// Query to fetch all section data
$query_sections = mysqli_query($conn, "
    SELECT * FROM sections
") or die("Error fetching section information: " . mysqli_error($conn));

if (isset($_POST['add_course'])) {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_department = mysqli_real_escape_string($conn, $_POST['course_department']);
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);

    $insert_course = "INSERT INTO courses (course_name, course_department, course_code) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_course);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $course_name, $course_department, $course_code);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Course added successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            error_log("Error adding course: " . mysqli_error($conn));
            echo "<script>alert('Error adding course.');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Error preparing statement: " . mysqli_error($conn));
    }
}

if (isset($_POST['add_section'])) {
    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);

    $insert_section = "INSERT INTO sections (section_name) VALUES ('$section_name')";
    if (mysqli_query($conn, $insert_section)) {
        echo "<script>alert('Section added successfully!');</script>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Error adding section: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .navbar-section {
            background-color: #2B547E;
        }

        .navbar-section .navbar-brand,
        .navbar-section .nav-link {
            color: #fff !important;
        }

        .navbar-section .nav-link:hover {
            text-decoration: underline;
        }

        .table {
            margin-top: 20px;
        }

        .card {
            margin-top: 20px;
            background-color: #f8f9fa;
            /* Light gray background for cards */
            border: 1px solid #dee2e6;
            /* Border color */
        }

        .card-header {
            background-color: #2B547E;
            /* Dark blue background for card headers */
            color: #fff;
            /* White text for card headers */
        }

        .card-body {
            padding: 20px;
            /* Padding for card body */
        }
    </style>
</head>

<body>

    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Dashboard</a>
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
            <div class="card-header">
                <h1 class="mb-0">SQL Query</h1>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="sql_query" class="form-label">Enter SQL Query</label>
                        <textarea class="form-control" id="sql_query" name="sql_query" rows="5" required></textarea>
                        <small class="form-text text-muted">Make sure your query is valid and follows SQL
                            syntax.</small>
                    </div>
                    <button type="submit" name="run_query" class="btn btn-primary">Run Query</button>
                </form>
            </div>
        </div>
        <?php if (isset($_POST['run_query'])): ?>
            <div class="card mt-5">
                <div class="card-header">
                    <h2 class="mb-0">Query Results</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <?php
                                    $sql_query = $_POST['sql_query'];
                                    $result = mysqli_query($conn, $sql_query);
                                    if ($result) {
                                        $fields = mysqli_fetch_fields($result);
                                        foreach ($fields as $field) {
                                            echo "<th>" . htmlspecialchars($field->name) . "</th>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='100%'>Error: " . mysqli_error($conn) . "</td></tr>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        foreach ($row as $value) {
                                            echo "<td>" . htmlspecialchars($value) . "</td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <section class="container mt-5">
            <h1 class="mt-4">Students Information</h1>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Year</th>
                            <th>Section</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student_info = mysqli_fetch_assoc($query_students)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student_info['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['email']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['gender']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['year']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($student_info['course_name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            </div>
            <h1 class="mt-4">Instructors Information</h1>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Instructors Name</th>
                            <th>Department</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($instructor_info = mysqli_fetch_assoc($query_instructors)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($instructor_info['username']); ?></td>
                                <td><?php echo htmlspecialchars($instructor_info['department']); ?></td>
                                <td><?php echo htmlspecialchars($instructor_info['email']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Courses Information</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Course Department</th>
                                    <th>Course Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($course_info = mysqli_fetch_assoc($query_courses)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course_info['id']); ?></td>
                                        <td><?php echo htmlspecialchars($course_info['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($course_info['course_department']); ?></td>
                                        <td><?php echo htmlspecialchars($course_info['course_code']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Add Course Form -->
                    <h2 class="mt-4">Add Course</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" required>
                            <label for="course_department" class="form-label">Course Department</label>
                            <input type="text" class="form-control" id="course_department" name="course_department"
                                required>
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="course_code" name="course_code" required>
                        </div>
                        <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Sections Information</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Section ID</th>
                                    <th>Section Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($section_info = mysqli_fetch_assoc($query_sections)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($section_info['id']); ?></td>
                                        <td><?php echo htmlspecialchars($section_info['section_name']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Add Section Form -->
                    <h2 class="mt-4">Add Section</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="section_name" class="form-label">Section Name</label>
                            <input type="text" class="form-control" id="section_name" name="section_name" required>
                        </div>
                        <button type="submit" name="add_section" class="btn btn-primary">Add Section</button>
                    </form>
                </div>
            </div>
            <br>

        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>