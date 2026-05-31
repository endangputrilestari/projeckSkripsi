<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('cf_env')) {
    function cf_env($key, $default = '')
    {
        $value = getenv($key);
        if ($value === false && isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }
        if ($value === false && isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }

        return ($value === false || $value === '') ? $default : $value;
    }
}

if (!function_exists('cf_bool_env')) {
    function cf_bool_env($key, $default = false)
    {
        $value = cf_env($key, $default ? 'true' : 'false');
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

$scheme = 'http';
if (
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
) {
    $scheme = 'https';
}

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$detectedBaseUrl = $scheme . '://' . $host;

defined('CF_BASE_URL') OR define('CF_BASE_URL', rtrim(cf_env('CF_BASE_URL', $detectedBaseUrl), '/'));
defined('CF_DB_HOST') OR define('CF_DB_HOST', cf_env('CF_DB_HOST', 'localhost'));
defined('CF_DB_NAME') OR define('CF_DB_NAME', cf_env('CF_DB_NAME', 'candidatefinder'));
defined('CF_DB_USER') OR define('CF_DB_USER', cf_env('CF_DB_USER', 'root'));
defined('CF_DB_PASSWORD') OR define('CF_DB_PASSWORD', cf_env('CF_DB_PASSWORD', ''));
defined('CF_DB_PORT') OR define('CF_DB_PORT', cf_env('CF_DB_PORT', '3306'));
defined('CF_DB_PREFIX') OR define('CF_DB_PREFIX', cf_env('CF_DB_PREFIX', ''));
defined('CF_DB_TYPE') OR define('CF_DB_TYPE', cf_env('CF_DB_TYPE', 'mysqli'));
defined('CF_VIEW') OR define('CF_VIEW', cf_env('CF_VIEW', 'beta'));
defined('CF_DEMO') OR define('CF_DEMO', cf_bool_env('CF_DEMO', false));
defined('CF_SESSION_DRIVER') OR define('CF_SESSION_DRIVER', cf_env('CF_SESSION_DRIVER', defined('CF_SERVERLESS') ? 'database' : 'files'));
defined('CF_SESSION_SAVE_PATH') OR define('CF_SESSION_SAVE_PATH', cf_env('CF_SESSION_SAVE_PATH', defined('CF_SERVERLESS') ? 'ci_sessions' : ''));
defined('CF_STORAGE_DRIVER') OR define('CF_STORAGE_DRIVER', cf_env('CF_STORAGE_DRIVER', defined('CF_SERVERLESS') ? 'external' : 'local'));
