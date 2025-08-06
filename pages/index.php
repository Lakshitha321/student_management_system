<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management</title>
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
            <h2 style="color: #667eea; margin-bottom: 2rem; font-size: 2.5rem;">Dashboard</h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <p class="stat-number"><?php echo count(getAllStudents()); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Courses</h3>
                    <p class="stat-number"><?php echo count(getAllCourses()); ?></p>
                </div>
            </div>
            
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="add_student.php" class="btn btn-primary">Add New Student</a>
                    <a href="add_course.php" class="btn btn-secondary">Add New Course</a>
                    <a href="students.php" class="btn btn-outline">View All Students</a>
                    <a href="courses.php" class="btn btn-outline">View All Courses</a>
                </div>
            </div>
            
            <div class="recent-activity">
                <h3>Recent Students</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recent_students = array_slice(getAllStudents(), 0, 5);
                            foreach ($recent_students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['course_name'] ?? 'Not Assigned'); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recent_students)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No students found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Student Management System. All rights reserved.</p>
    </footer>
</body>
</html>