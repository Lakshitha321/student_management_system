<?php 
require_once "config.php";

if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit();
}

$student_id = $_GET['id'];
$student = getStudentById($student_id);

if (!$student) {
    header('Location: students.php');
    exit();
}

// Get course details if student has a course
$course = null;
if ($student['course_id']) {
    $course = getCourseById($student['course_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - Student Management</title>
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
                <h2>Student Details</h2>
                <div>
                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning">Edit Student</a>
                    <a href="students.php" class="btn btn-outline">Back to Students</a>
                </div>
            </div>
            
            <div class="detail-card">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Student ID</div>
                        <div class="detail-value"><?php echo htmlspecialchars($student['student_id']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value"><?php echo htmlspecialchars($student['name']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value"><?php echo htmlspecialchars($student['email']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value"><?php echo htmlspecialchars($student['phone'] ?: 'Not provided'); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Course</div>
                        <div class="detail-value">
                            <?php if ($course): ?>
                                <?php echo htmlspecialchars($course['course_name']); ?> (<?php echo htmlspecialchars($course['course_code']); ?>)
                            <?php else: ?>
                                Not Assigned
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Registration Date</div>
                        <div class="detail-value"><?php echo date('F j, Y', strtotime($student['created_at'])); ?></div>
                    </div>
                </div>
                
                <?php if ($student['address']): ?>
                <div class="detail-item">
                    <div class="detail-label">Address</div>
                    <div class="detail-value"><?php echo nl2br(htmlspecialchars($student['address'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Management System. All rights reserved.</p>
    </footer>
</body>
</html>