#!/usr/bin/php
<?php
// my_cron_script.php

// Define the absolute path for any files you create/write to.
$logFile = '/Applications/Blog-project-1/cron_log.txt';

// The task you want to perform.
$timestamp = date('Y-m-d H:i:s');
$message = "Cron job ran successfully at: $timestamp\n";

// Example: Append a message to a log file.
if (file_put_contents($logFile, $message, FILE_APPEND) === false) {
    // If writing fails, print an error message that cron can capture.
    echo "Error writing to log file.\n";
} else {
    // Optional: Print a success message (cron will email this by default).
    echo "Task completed successfully.\n";
}

// Exit explicitly (good practice for CLI scripts).
exit(0);