<?php


declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once 'vendor/autoload.php';
use NetVOD\src\dispatcher\Dispatcher;
use NetVOD\src\repository\Repository;

session_start();

Repository::setConfig("Config.ini");

//on verifie si la personne cherche à se connecter
$demandeConn = (isset($_GET['action']) and ($_GET['action'] === 'register' || $_GET['action']=="verify"));

//on vérifie si il y a déja un utilisateur en session
if (!isset($_SESSION['user']) and !$demandeConn) {
    $action = "login";
} else {
    $action = $_GET['action'] ?? 'default';
}

$dispatcher = new Dispatcher($action);
$dispatcher->run();



