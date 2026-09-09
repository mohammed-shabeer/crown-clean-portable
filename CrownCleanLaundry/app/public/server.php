<?php
/**
 * Router for the bundled PHP server.
 *
 * 1. Block direct requests to non-public source files.
 * 2. Serve existing files under public/ directly.
 * 3. Everything else goes through the application.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Never serve these even though they exist on disk.
$blocked = [
    '/server.php',
    '/index.php',
    '/.env',
    '/.htaccess',
    '/composer.json',
    '/.ccl',
];
$lower = strtolower($uri);
if (in_array($lower, $blocked, true)) {
    http_response_code(404);
    exit;
}

// Block any dot-file or obvious sensitive extension under public/.
if (preg_match('#(^|/)\.(?!well-known)#', $uri)) {
    http_response_code(404);
    exit;
}

// Serve real files directly (CSS, JS, images, fonts...).
if ($uri !== '/' && $uri !== '' && is_file(__DIR__ . $uri)) {
    return false;
}

require __DIR__ . '/index.php';
