<?php
/**
 * Check Railway MySQL Database Status
 */

$host = 'interchange.proxy.rlwy.net';
$port = '39149';
$database = 'railway';
$username = 'root';
$password = 'tCSMQmQovUhWTYsSIQfdMfflcpANrCWN';

echo "Connecting to MySQL...\n";

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    
    echo "Connected successfully!\n\n";
    
    // Show tables
    echo "Tables in database 'railway':\n";
    echo "============================\n";
    $result = $conn->query("SHOW TABLES");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            echo "  - " . $row[0] . "\n";
        }
        echo "\nTotal tables: " . $result->num_rows . "\n";
    } else {
        echo "No tables found.\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
