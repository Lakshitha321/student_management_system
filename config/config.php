<?php
// config.php - Database Configuration
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management";

// Create connection
function getConnection() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Initialize database and tables
function initDatabase() {
    global $servername, $username, $password, $dbname;
    
    // Connect without database first
    $conn = new mysqli($servername, $username, $password);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->query($sql);
    $conn->select_db($dbname);
    
    // Create courses table first (referenced by students)
    $sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(20) NOT NULL UNIQUE,
        course_name VARCHAR(100) NOT NULL,
        description TEXT,
        duration VARCHAR(50),
        fee DECIMAL(10,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Create students table
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        phone VARCHAR(15),
        address TEXT,
        course_id INT(6) UNSIGNED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
    )";
    $conn->query($sql);
    
    $conn->close();
}

// CRUD Functions for Students
function createStudent($student_id, $name, $email, $phone, $address, $course_id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, phone, address, course_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $student_id, $name, $email, $phone, $address, $course_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

function getAllStudents() {
    $conn = getConnection();
    
    $sql = "SELECT s.*, c.course_name 
            FROM students s 
            LEFT JOIN courses c ON s.course_id = c.id 
            ORDER BY s.name";
    
    $result = $conn->query($sql);
    $students = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    
    $conn->close();
    return $students;
}

function getStudentById($id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $student = null;
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();
    return $student;
}

function updateStudent($id, $student_id, $name, $email, $phone, $address, $course_id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("UPDATE students SET student_id = ?, name = ?, email = ?, phone = ?, address = ?, course_id = ? WHERE id = ?");
    $stmt->bind_param("sssssii", $student_id, $name, $email, $phone, $address, $course_id, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

function deleteStudent($id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

// CRUD Functions for Courses
function createCourse($course_code, $course_name, $description, $duration, $fee) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, description, duration, fee) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $course_code, $course_name, $description, $duration, $fee);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

function getAllCourses() {
    $conn = getConnection();
    
    $sql = "SELECT c.*, COUNT(s.id) as student_count 
            FROM courses c 
            LEFT JOIN students s ON c.id = s.course_id 
            GROUP BY c.id 
            ORDER BY c.course_name";
    
    $result = $conn->query($sql);
    $courses = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    
    $conn->close();
    return $courses;
}

function getCourseById($id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $course = null;
    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();
    return $course;
}

function updateCourse($id, $course_code, $course_name, $description, $duration, $fee) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("UPDATE courses SET course_code = ?, course_name = ?, description = ?, duration = ?, fee = ? WHERE id = ?");
    $stmt->bind_param("ssssdi", $course_code, $course_name, $description, $duration, $fee, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

function deleteCourse($id) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        return "Error: " . $error;
    }
}

// Initialize database
initDatabase();
?>