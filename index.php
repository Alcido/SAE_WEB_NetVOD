<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
use NetVOD\src\dispatcher\Dispatcher;

session_start();

$demandeConn = (isset($_GET['action']) and $_GET['action'] === 'register');

if (!isset($_SESSION['user']) and !$demandeConn) {
    $action = "login";
} else {
    $action = $_GET['action'] ?? 'default';
}

$dispatcher = new Dispatcher($action);
$dispatcher->run();



