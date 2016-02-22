<?php

//require '../App/Controllers/Index.php';
require '../App/Controllers/Posts.php';

require_once '../core/Router.php';

$router = new Router();

$router->add('', ['controller' => 'Index', 'action' => 'index']);
$router->add('posts/add', ['controller' => 'Posts', 'action' => 'addNew']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);


?>