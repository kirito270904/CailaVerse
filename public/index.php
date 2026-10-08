<?php
session_start();
date_default_timezone_set('Asia/Manila');

$root = dirname(__DIR__);
require $root . '/config/database.php';
require $root . '/app/helpers.php';

spl_autoload_register(function ($class) use ($root) {
    foreach (['controllers', 'models'] as $dir) {
        $file = $root . '/app/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

$routes = [
    'auth'    => 'AuthController',
    'post'    => 'PostController',
    'comment' => 'CommentController',
    'profile' => 'ProfileController',
    'search'  => 'SearchController',
    'follow'  => 'FollowController',
];

$c = $_GET['c'] ?? 'post';
$a = $_GET['a'] ?? 'feed';

if (!is_string($c) || !is_string($a) || !isset($routes[$c]) || !preg_match('/^[a-z]+$/', $a)) {
    http_response_code(404);
    exit('Page not found.');
}

$controller = new $routes[$c]();
if (!method_exists($controller, $a) || !(new ReflectionMethod($controller, $a))->isPublic()) {
    http_response_code(404);
    exit('Page not found.');
}

$controller->$a();
