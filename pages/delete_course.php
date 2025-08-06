<?php 
require_once "config.php";

if (!isset($_GET['id'])) {
    header('Location: courses.php');
    exit();
}

$course_id = $_GET['id'];
$course = getCourseById($course_id);

if (!$course) {
    header('Location: courses.php?error=Course not found');
    exit();
}

// Check if course has enrolled students
$conn = getConnection();
$stmt = $conn->prepare("SELECT COUNT(*) as student_count FROM students WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$student_count = $result->fetch_assoc()['student_count'];
$stmt->close();
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = deleteCourse($course_id);
    if ($result === 'success') {
        header('Location: courses.php?success=Course deleted successfully');
    } else {
        header('Location: courses.php?error=' . urlencode($result));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Course - Student Management</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <h1>Student Management System</h1>
            <nav>
                <a href="index.php">Dashboard</a>
                <a href="students.php">Students</a>
                <a href="courses.php">Courses</a>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div class="page-header">
                <h2>Delete Course</h2>
                <a href="courses.php" class="btn btn-outline">Back to Courses</a>
            </div>
            
            <div class="form-container">
                <div class="alert alert-error">
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                
                <?php if ($student_count > 0): ?>
                <div class="alert alert-error">
                    <strong>Note:</strong> This course has <?php echo $student_count; ?> enrolled student(s). 
                    Deleting this course will remove the course assignment from these students.
                </div>
                <?php endif; ?>
                
                <h3>Are you sure you want to delete this course?</h3>
                <p><strong>Course Name:</strong> <?php echo htmlspecialchars($course['course_name']); ?></p>
                <p><strong>Course Code:</strong> <?php echo htmlspecialchars($course['course_code']); ?></p>
                <p><strong>Fee:</strong> $<?php echo number_format($course['fee'], 2); ?></p>
                
                <form method="post" style="margin-top: 2rem;">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">Yes, Delete Course</button>
                        <a href="courses.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Management System. All rights reserved.</p>
    </footer>
</body>
</html>