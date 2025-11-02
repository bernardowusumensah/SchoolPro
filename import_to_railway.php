<?php
/**
 * Import Database to Railway MySQL
 * Run this with: railway run php import_to_railway.php
 */

// Always use public URL for external connection
$host = 'interchange.proxy.rlwy.net';
$port = '39149';
$database = 'railway';
$username = 'root';
$password = 'tCSMQmQovUhWTYsSIQfdMfflcpANrCWN';

echo "Connecting to MySQL...\n";
echo "Host: $host:$port\n";
echo "Database: $database\n";

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    
    echo "Connected successfully!\n\n";
    
    // Read SQL file
    $sqlFile = 'schoolpro1 (2).sql';
    
    if (!file_exists($sqlFile)) {
        die("SQL file not found: $sqlFile\n");
    }
    
    echo "Reading SQL file: $sqlFile\n";
    $sql = file_get_contents($sqlFile);
    
    if ($sql === false) {
        die("Error reading SQL file\n");
    }
    
    echo "Importing database...\n";
    
    // Set multi-query mode
    $conn->set_charset("utf8mb4");
    
    // Execute multi-query
    if ($conn->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $conn->store_result()) {
                $result->free();
            }
            
            // Print divider between results
            if ($conn->more_results()) {
                echo ".";
            }
        } while ($conn->next_result());
        
        echo "\n";
        $success = true;
        $errors = 0;
    } else {
        echo "Error executing SQL: " . $conn->error . "\n";
        $success = false;
        $errors = 1;
    }
    
    // Check for final error
    if ($conn->error) {
        echo "Final error: " . $conn->error . "\n";
    }
    
    echo "\n✅ Import completed!\n";
    if ($success) {
        echo "Database imported successfully!\n";
    } else {
        echo "Import completed with errors\n";
    }
    echo "Errors: $errors\n";
    
    // Show tables
    echo "\nTables in database:\n";
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        echo "  - " . $row[0] . "\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
