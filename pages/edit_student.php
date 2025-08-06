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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id_field = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $course_id = !empty($_POST['course_id']) ? $_POST['course_id'] : null;
    
    if (empty($student_id_field) || empty($name) || empty($email)) {
        $error = 'Please fill in all required fields';
    } else {
        $result = updateStudent($student_id, $student_id_field, $name, $email, $phone, $address, $course_id);
        if ($result === 'success') {
            $success = 'Student updated successfully!';
            // Refresh student data
            $student = getStudentById($student_id);
        } else {
            $error = $result;
        }
    }
}

$courses = getAllCourses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management</title>
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
                <h2>Edit Student</h2>
                <a href="students.php" class="btn btn-outline">Back to Students</a>
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
                        <label for="student_id">Student ID *</label>
                        <input type="text" id="student_id" name="student_id" 
                               value="<?php echo htmlspecialchars($student['student_id']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="course_id">Course</label>
                        <select id="course_id" name="course_id">
                            <option value="">Select a course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>"
                                    <?php echo ($student['course_id'] == $course['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Student</button>
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