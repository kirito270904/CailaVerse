<?php
/**
 * CailaVerse — Database Configuration
 *
 * For LOCAL development (XAMPP), keep values below as-is.
 *
 * For PRODUCTION hosting, you have two options:
 *   Option A – Edit the constants directly in this file.
 *   Option B – Define these constants in your server's environment variables
 *              (php.ini / .htaccess SetEnv / cPanel Environment Variables)
 *              and they will be picked up automatically.
 *
 * Environment variable names:
 *   DB_HOST, DB_NAME, DB_USER, DB_PASS
 */

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'social_app');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
    return $pdo;
}
