<?php
/**
 * Router script for PHP development server
 * This handles routing for uploads directory to force through FileController
 */

// Log the request for debugging
$logFile = dirname(__DIR__) . '/writable/logs/router.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Router called for: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

// Get the requested URI
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

file_put_contents($logFile, date('Y-m-d H:i:s') . " - Parsed path: " . $path . "\n", FILE_APPEND);

// If it's an uploads request, redirect to index.php with the proper route
if (preg_match('/^\/uploads\/(.+)$/', $path, $matches)) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Matched uploads pattern, routing to FileController\n", FILE_APPEND);
    $_SERVER['REQUEST_URI'] = '/uploads/' . $matches[1];
    $_SERVER['PATH_INFO'] = '/uploads/' . $matches[1];
    require 'index.php';
    return true;
}

// For all other requests, check if file exists
if (file_exists(__DIR__ . $path) && is_file(__DIR__ . $path)) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - File exists, serving directly\n", FILE_APPEND);
    return false; // Let PHP serve the file directly
}

// Otherwise, route through CodeIgniter
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Routing through CodeIgniter\n", FILE_APPEND);
require 'index.php';
return true;