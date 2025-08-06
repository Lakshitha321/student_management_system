<?php 
require_once "config.php";

if (!isset($_GET['id'])) {
    header('Location: students.php');
    exit();
}

$student_id = $_GET['id'];
$student = getStudentById($student_id);

if (!$student) {
    header('Location: students.php?error=Student not found');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = deleteStudent($student_id);
    if ($result === 'success') {
        header('Location: students.php?success=Student deleted successfully');
    } else {
        header('Location: students.php?error=' . urlencode($result));
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student - Student Management</title>
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
                <h2>Delete Student</h2>
                <a href="students.php" class="btn btn-outline">Back to Students</a>
            </div>
            
            <div class="form-container">
                <div class="alert alert-error">
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                
                <h3>Are you sure you want to delete this student?</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                
                <form method="post" style="margin-top: 2rem;">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
                        <a href="students.php" class="btn btn-secondary">Cancel</a>
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