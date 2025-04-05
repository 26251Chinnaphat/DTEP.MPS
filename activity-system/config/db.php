<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'activity_system';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้างตารางหากยังไม่มี
$sql = "CREATE TABLE IF NOT EXISTS staff_registrations (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    nickname VARCHAR(30) NOT NULL,
    staff_id VARCHAR(20) NOT NULL,
    birth_date DATE NOT NULL,
    age INT(3),
    image_path VARCHAR(255),
    year_level VARCHAR(50) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}
?>