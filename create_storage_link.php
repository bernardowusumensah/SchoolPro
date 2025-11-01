<?php
/**
 * TEMPORARY FILE - Create Storage Symlink for AWS Deployment
 * 
 * Access this file via: https://your-ec2-domain.com/create_storage_link.php
 * DELETE THIS FILE after running it once!
 */

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

// Remove existing symlink/directory if it exists
if (file_exists($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "✓ Removed existing symlink<br>";
    } else {
        echo "⚠️ Warning: 'public/storage' exists but is not a symlink. Please remove it manually.<br>";
        exit;
    }
}

// Create the symlink
if (symlink($target, $link)) {
    echo "✅ SUCCESS! Storage symlink created successfully!<br>";
    echo "Target: $target<br>";
    echo "Link: $link<br><br>";
    echo "<strong>⚠️ IMPORTANT: DELETE THIS FILE NOW for security!</strong><br>";
    echo "Run: <code>rm create_storage_link.php</code> or delete it via FTP/Git";
} else {
    echo "❌ ERROR: Failed to create symlink. You may need SSH access.<br>";
    echo "Try running via SSH: <code>php artisan storage:link</code>";
}
