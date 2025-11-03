<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';
use NetVOD\src\dispatcher\Dispatcher;

session_start();

//on verifie si la personne cherche à se connecter
$demandeConn = (isset($_GET['action']) and $_GET['action'] === 'register');

//on vérifie si il y a déja un utilisateur en session
if (!isset($_SESSION['user']) and !$demandeConn) {
    $action = "login";
} else {
    $action = $_GET['action'] ?? 'default';
}

$dispatcher = new Dispatcher($action);
$dispatcher->run();



