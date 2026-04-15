<?php
$content = file_get_contents(__DIR__ . '/routes/web.php');
echo (strpos($content, '/api/push/public-key') !== false ? 'has-push' : 'no-push') . PHP_EOL;
echo (strpos($content, '/api/push-test') !== false ? 'has-push-test' : 'no-push-test') . PHP_EOL;
