<?php

require_once 'loader/AutoLoader.php';
(new iutnc\NetVOD\Loader\AutoLoader("iutnc\\NetVOD\\", __DIR__))->register();
if (!isset($_GET['action'])) {$_GET['action'] = "menu";}
(new Dispatcher($_GET['action']))->run();
