<?php 
require_once "config.php";

$message = '';
$message_type = '';

// Handle messages from URL parameters
if (isset($_GET['success'])) {
    $message = $_GET['success'];
    $message_type = 'success';
} elseif (isset($_GET['error'])) {
    $message = $_GET['error'];
    $message_type = 'error';
}

$students = getAllStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Student Management</title>
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
                <h2>Students Management</h2>
                <a href="add_student.php" class="btn btn-primary">Add New Student</a>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($student['course_name'] ?? 'Not Assigned'); ?></td>
                                <td class="actions">
                                    <a href="view_student.php?id=<?php echo $student['id']; ?>" class="btn btn-small btn-info">View</a>
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-small btn-warning">Edit</a>
                                    <a href="delete_student.php?id=<?php echo $student['id']; ?>" 
                                       class="btn btn-small btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No students found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Management System. All rights reserved.</p>
    </footer>
</body>
</html>