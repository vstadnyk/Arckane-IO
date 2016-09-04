<?
require_once __DIR__ . '/start.php';
$router = new Router();
$router->init();
$template = new View($router->page, $router->headers);
$template->init();
$template->extend($router->page->template.'/functions.php');
$template->render();