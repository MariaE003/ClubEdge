<?php

$env = static function (string $key, string $default): string {
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
};

define('DB_HOST', $env('DB_HOST', 'localhost'));
define('DB_NAME', $env('DB_NAME', 'clubedge'));
define('DB_USER', $env('DB_USER', 'postgres'));
define('DB_PASS', $env('DB_PASS', ',ouhsinerouqki'));
define('DB_PORT', $env('DB_PORT', '5432'));
