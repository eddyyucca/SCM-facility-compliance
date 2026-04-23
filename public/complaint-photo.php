<?php

$filename = basename((string) ($_GET['f'] ?? ''));

if ($filename === '' || ! preg_match('/^[A-Za-z0-9._-]+\.(jpe?g|png|webp)$/i', $filename)) {
    http_response_code(404);
    exit('Not found');
}

$basePath = dirname(__DIR__);
$candidatePaths = [];

if (is_file($basePath . '/vendor/autoload.php') && is_file($basePath . '/bootstrap/app.php')) {
    require_once $basePath . '/vendor/autoload.php';
    require_once $basePath . '/bootstrap/app.php';

    if (function_exists('storage_path')) {
        $candidatePaths[] = storage_path('app/public/complaints/' . $filename);
    }
}

$candidatePaths[] = $basePath . DIRECTORY_SEPARATOR . 'storage'
    . DIRECTORY_SEPARATOR . 'app'
    . DIRECTORY_SEPARATOR . 'public'
    . DIRECTORY_SEPARATOR . 'complaints'
    . DIRECTORY_SEPARATOR . $filename;

$candidatePaths[] = __DIR__ . DIRECTORY_SEPARATOR . 'storage'
    . DIRECTORY_SEPARATOR . 'complaints'
    . DIRECTORY_SEPARATOR . $filename;

$candidatePaths[] = __DIR__ . DIRECTORY_SEPARATOR . 'storage'
    . DIRECTORY_SEPARATOR . 'app'
    . DIRECTORY_SEPARATOR . 'public'
    . DIRECTORY_SEPARATOR . 'complaints'
    . DIRECTORY_SEPARATOR . $filename;

$path = null;
foreach (array_unique($candidatePaths) as $candidatePath) {
    if (is_file($candidatePath) && is_readable($candidatePath)) {
        $path = $candidatePath;
        break;
    }
}

if (! $path) {
    if (($_GET['debug'] ?? '') === '1') {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "File not found: {$filename}\n\nChecked paths:\n";
        foreach (array_unique($candidatePaths) as $candidatePath) {
            echo '- ' . $candidatePath . ' | exists=' . (is_file($candidatePath) ? 'yes' : 'no') . ' | readable=' . (is_readable($candidatePath) ? 'yes' : 'no') . "\n";
        }
        exit;
    }

    http_response_code(404);
    exit('Not found');
}

$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$contentType = match ($extension) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
    default => 'application/octet-stream',
};

header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=604800');
readfile($path);
