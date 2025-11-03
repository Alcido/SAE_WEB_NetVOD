<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
use NetVOD\src\dispatcher\Dispatcher;

session_start();

$action = $_GET['action'] ?? "menu";

$dispatcher = new Dispatcher($action);
$dispatcher->run();



