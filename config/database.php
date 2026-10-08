<?php
/**
 * CailaVerse — Database Configuration
 *
 * Supports multiple environments automatically:
 *   LOCAL (XAMPP)  → uses defaults: localhost / social_app / root / (no password)
 *   Railway        → reads MYSQLHOST, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD, MYSQLPORT
 *   Other hosting  → set DB_HOST, DB_NAME, DB_USER, DB_PASS environment variables
 *
 * To override for shared hosting: edit the fallback values in getenv('...') ?: 'value'
 */

// Railway uses MYSQL* prefix; other hosts use DB_* prefix; fallback to XAMPP defaults
$_dbHost = getenv('MYSQLHOST')     ?: (getenv('DB_HOST') ?: 'localhost');
$_dbName = getenv('MYSQLDATABASE') ?: (getenv('DB_NAME') ?: 'social_app');
$_dbUser = getenv('MYSQLUSER')     ?: (getenv('DB_USER') ?: 'root');
$_dbPass = getenv('MYSQLPASSWORD') ?: (getenv('DB_PASS') ?: '');
$_dbPort = getenv('MYSQLPORT')     ?: (getenv('DB_PORT') ?: '3306');

define('DB_HOST', $_dbHost);
define('DB_NAME', $_dbName);
define('DB_USER', $_dbUser);
define('DB_PASS', $_dbPass);
define('DB_PORT', $_dbPort);

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST
             . ';port=' . DB_PORT
             . ';dbname=' . DB_NAME
             . ';charset=utf8mb4';
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
