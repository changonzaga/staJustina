<?php
/**
 * Direct file serving script for uploads directory
 * This bypasses CodeIgniter routing issues
 */

// Log the request for debugging
$logFile = __DIR__ . '/../writable/logs/file_serve.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Request URI: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

// Get the requested file path
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

file_put_contents($logFile, date('Y-m-d H:i:s') . " - Parsed path: " . $path . "\n", FILE_APPEND);

// Remove the /file_serve.php part and get the actual file path
if (strpos($path, '/file_serve.php/') === 0) {
    $filePath = substr($path, 15); // Remove '/file_serve.php/'
} elseif (strpos($path, '/uploads/') === 0) {
    $filePath = substr($path, 9); // Remove '/uploads/'
} else {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Invalid path format\n", FILE_APPEND);
    http_response_code(404);
    exit('File not found');
}

file_put_contents($logFile, date('Y-m-d H:i:s') . " - File path: " . $filePath . "\n", FILE_APPEND);

// Build full file path
$fullPath = __DIR__ . '/uploads/' . $filePath;
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Full path: " . $fullPath . "\n", FILE_APPEND);

// Security check - ensure file is within uploads directory
$realPath = realpath($fullPath);
$uploadsPath = realpath(__DIR__ . '/uploads/');

if (!$realPath || !$uploadsPath || strpos($realPath, $uploadsPath) !== 0) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Security check failed\n", FILE_APPEND);
    http_response_code(403);
    exit('Access denied');
}

// Check if file exists
if (!file_exists($fullPath) || !is_file($fullPath)) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - File not found: " . $fullPath . "\n", FILE_APPEND);
    http_response_code(404);
    exit('File not found');
}

// Read file content to detect actual type
$fileContent = file_get_contents($fullPath);

/**
 * Detect content type based on file content
 */
function detectContentType($content, $filePath) {
    // Check if content starts with SVG or contains SVG tags
    $trimmedContent = trim($content);
    if (strpos($trimmedContent, '<svg') === 0 || 
        strpos($content, '<svg') !== false || 
        strpos($content, 'xmlns="http://www.w3.org/2000/svg"') !== false) {
        return 'image/svg+xml';
    }
    
    // Use finfo to detect MIME type
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mimeType = finfo_buffer($finfo, $content);
            finfo_close($finfo);
            
            // If finfo detects it as SVG, use that
            if ($mimeType === 'image/svg+xml') {
                return $mimeType;
            }
            
            // For other types, only use if it's not generic
            if ($mimeType && $mimeType !== 'application/octet-stream' && $mimeType !== 'text/plain') {
                // But if content looks like SVG, override finfo
                if (strpos($content, '<svg') !== false || strpos($content, 'xmlns') !== false) {
                    return 'image/svg+xml';
                }
                return $mimeType;
            }
        }
    }
    
    // Fallback to file extension
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    
    // If content looks like SVG but has wrong extension, return SVG
    if (strpos($content, '<svg') !== false || strpos($content, 'xmlns') !== false) {
        return 'image/svg+xml';
    }
    
    return $mimeTypes[$extension] ?? 'application/octet-stream';
}

$contentType = detectContentType($fileContent, $fullPath);
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Detected content type: " . $contentType . "\n", FILE_APPEND);

// Set appropriate headers
header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: public, max-age=3600');
header('Access-Control-Allow-Origin: *');

// For SVG files, ensure proper content type
if ($contentType === 'image/svg+xml') {
    header('Content-Type: image/svg+xml; charset=utf-8');
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Set SVG content type\n", FILE_APPEND);
}

file_put_contents($logFile, date('Y-m-d H:i:s') . " - Sending file content\n", FILE_APPEND);

// Send file content
echo $fileContent;