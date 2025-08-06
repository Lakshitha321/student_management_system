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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);
    $fee = floatval($_POST['fee']);
    
    if (empty($course_code) || empty($course_name)) {
        $error = 'Please fill in all required fields';
    } else {
        $result = updateCourse($course_id, $course_code, $course_name, $description, $duration, $fee);
        if ($result === 'success') {
            $success = 'Course updated successfully!';
            // Refresh course data
            $course = getCourseById($course_id);
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Student Management</title>
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
                <h2>Edit Course</h2>
                <a href="courses.php" class="btn btn-outline">Back to Courses</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="post">
                    <div class="form-group">
                        <label for="course_code">Course Code *</label>
                        <input type="text" id="course_code" name="course_code" 
                               value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="course_name">Course Name *</label>
                        <input type="text" id="course_name" name="course_name" 
                               value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" placeholder="e.g., 6 months, 1 year"
                               value="<?php echo htmlspecialchars($course['duration']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fee">Course Fee</label>
                        <input type="number" id="fee" name="fee" step="0.01" min="0"
                               value="<?php echo $course['fee']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Course</button>
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