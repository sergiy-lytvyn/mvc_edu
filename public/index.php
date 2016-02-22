<?php


//require '../App/Controllers/Posts.php

spl_autoload_register(function($class) {
    $root = dirname(__DIR__); //get parent directory
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $root . '/'. str_replace('\\', '/', $class) . '.php';
    }
});


$router = new Core\Router();

$router->add('', ['controller' => 'Index', 'action' => 'index']);
$router->add('post/add', ['controller' => 'Posts', 'action' => 'addNew']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);


?>