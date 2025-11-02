<?php
/**
 * Check Project Statuses on Railway
 */

$host = 'interchange.proxy.rlwy.net';
$port = '39149';
$database = 'railway';
$username = 'root';
$password = 'tCSMQmQovUhWTYsSIQfdMfflcpANrCWN';

echo "Connecting to Railway MySQL...\n";

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    
    echo "Connected successfully!\n\n";
    
    // Check if projects table exists
    $result = $conn->query("SHOW TABLES LIKE 'projects'");
    if ($result->num_rows == 0) {
        die("Projects table doesn't exist!\n");
    }
    
    echo "Projects in database:\n";
    echo "=====================\n\n";
    
    $result = $conn->query("SELECT id, title, status, student_id, supervisor_id, created_at FROM projects ORDER BY id DESC LIMIT 10");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . "\n";
            echo "Title: " . $row['title'] . "\n";
            echo "Status: " . $row['status'] . "\n";
            echo "Student: " . $row['student_id'] . "\n";
            echo "Supervisor: " . $row['supervisor_id'] . "\n";
            echo "Created: " . $row['created_at'] . "\n";
            echo "---\n";
        }
    } else {
        echo "No projects found.\n";
    }
    
    // Check all unique statuses
    echo "\nAll unique statuses in database:\n";
    $result = $conn->query("SELECT DISTINCT status, COUNT(*) as count FROM projects GROUP BY status");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "  - " . $row['status'] . " (" . $row['count'] . " projects)\n";
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
