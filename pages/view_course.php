<?php 
require_once "config.php";

if (!isset($_GET['id'])) {
    header('Location: courses.php');
    exit();
}

$course_id = $_GET['id'];
$course = getCourseById($course_id);

if (!$course) {
    header('Location: courses.php');
    exit();
}

// Get students enrolled in this course
$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM students WHERE course_id = ? ORDER BY name");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$enrolled_students = [];
while ($row = $result->fetch_assoc()) {
    $enrolled_students[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course - Student Management</title>
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
                <h2>Course Details</h2>
                <div>
                    <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning">Edit Course</a>
                    <a href="courses.php" class="btn btn-outline">Back to Courses</a>
                </div>
            </div>
            
            <div class="detail-card">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Course Code</div>
                        <div class="detail-value"><?php echo htmlspecialchars($course['course_code']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Course Name</div>
                        <div class="detail-value"><?php echo htmlspecialchars($course['course_name']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value"><?php echo htmlspecialchars($course['duration'] ?: 'Not specified'); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Course Fee</div>
                        <div class="detail-value">$<?php echo number_format($course['fee'], 2); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div