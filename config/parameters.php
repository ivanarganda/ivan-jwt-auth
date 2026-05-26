<?php 

// Load environment variables from .env file
$env_file_path = __DIR__ . '/../.env';

$env_contents = file_get_contents($env_file_path);
$lines = explode("\n", $env_contents);
foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue; // Skip empty lines and comments
    }
    list($key, $value) = explode('=', $line, 2);
    putenv("$key=$value");
}

// Database configuration using environment variables
define('DB_HOST', getenv('LOCAL_DB_HOST'));
define('DB_NAME', getenv('LOCAL_DB_NAME'));
define('DB_USER', getenv('LOCAL_DB_USER'));
define('DB_PASS', getenv('LOCAL_DB_PASS'));
define('DB_CHARSET', getenv('LOCAL_DB_CHARSET'));
define('DB_COLLATION', getenv('LOCAL_DB_COLLATION'));
define('DB_PORT', getenv('LOCAL_DB_PORT'));