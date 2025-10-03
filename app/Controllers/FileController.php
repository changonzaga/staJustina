<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class FileController extends BaseController
{
    /**
     * Serve files from uploads directory with proper content type detection
     */
    public function serve($path = null)
    {
        // Force logging to work by writing to a file
        $logFile = WRITEPATH . 'logs/filecontroller.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - FileController::serve called with path: " . $path . "\n", FILE_APPEND);
        
        if (!$path) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - No path provided\n", FILE_APPEND);
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Decode the path
        $path = urldecode($path);
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Decoded path: " . $path . "\n", FILE_APPEND);
        
        // Build full file path
        $filePath = FCPATH . 'uploads/' . $path;
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Full file path: " . $filePath . "\n", FILE_APPEND);
        
        // Security check - ensure file is within uploads directory
        $realPath = realpath($filePath);
        $uploadsPath = realpath(FCPATH . 'uploads/');
        
        if (!$realPath || !$uploadsPath || strpos($realPath, $uploadsPath) !== 0) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Security check failed\n", FILE_APPEND);
            return $this->response->setStatusCode(403, 'Access denied');
        }
        
        // Check if file exists
        if (!file_exists($filePath) || !is_file($filePath)) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - File not found: " . $filePath . "\n", FILE_APPEND);
            return $this->response->setStatusCode(404, 'File not found');
        }
        
        // Read file content to detect actual type
        $fileContent = file_get_contents($filePath);
        $contentType = $this->detectContentType($fileContent, $filePath);
        
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Detected content type: " . $contentType . "\n", FILE_APPEND);
        
        // Set appropriate headers
        $this->response->setHeader('Content-Type', $contentType);
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Cache-Control', 'public, max-age=3600');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        
        // For SVG files, ensure proper content type
        if ($contentType === 'image/svg+xml') {
            $this->response->setHeader('Content-Type', 'image/svg+xml; charset=utf-8');
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Set SVG content type\n", FILE_APPEND);
        }
        
        // Send file content
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Sending file content\n", FILE_APPEND);
        return $this->response->setBody($fileContent);
    }
    
    /**
     * Detect actual content type based on file content
     */
    private function detectContentType($content, $filePath)
    {
        // Log the content detection process
        $logFile = WRITEPATH . 'logs/filecontroller.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Starting content type detection\n", FILE_APPEND);
        
        // Check if content starts with SVG or contains SVG tags
        $trimmedContent = trim($content);
        if (strpos($trimmedContent, '<svg') === 0 || 
            strpos($content, '<svg') !== false || 
            strpos($content, 'xmlns="http://www.w3.org/2000/svg"') !== false) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Detected SVG content\n", FILE_APPEND);
            return 'image/svg+xml';
        }
        
        // Use finfo to detect MIME type
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mimeType = finfo_buffer($finfo, $content);
                finfo_close($finfo);
                
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - finfo detected: " . $mimeType . "\n", FILE_APPEND);
                
                // If finfo detects it as SVG, use that
                if ($mimeType === 'image/svg+xml') {
                    return $mimeType;
                }
                
                // For other types, only use if it's not generic
                if ($mimeType && $mimeType !== 'application/octet-stream' && $mimeType !== 'text/plain') {
                    // But if content looks like SVG, override finfo
                    if (strpos($content, '<svg') !== false || strpos($content, 'xmlns') !== false) {
                        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Overriding finfo with SVG\n", FILE_APPEND);
                        return 'image/svg+xml';
                    }
                    return $mimeType;
                }
            }
        }
        
        // Fallback: check file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - File extension: " . $extension . "\n", FILE_APPEND);
        
        // If content looks like SVG but has wrong extension, return SVG
        if (strpos($content, '<svg') !== false || strpos($content, 'xmlns') !== false) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Content looks like SVG, overriding extension\n", FILE_APPEND);
            return 'image/svg+xml';
        }
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'svg':
                return 'image/svg+xml';
            case 'pdf':
                return 'application/pdf';
            default:
                return 'application/octet-stream';
        }
    }
}