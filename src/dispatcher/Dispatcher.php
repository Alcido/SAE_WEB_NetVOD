<?php

namespace NetVOD\dispatcher;

use NetVOD\repository\Repository;

class Dispatcher
{
    /**
     * @throws \Exception
     */
    public static function run(){
        Repository::setConfig("/opt/lampp/htdocs/Config.ini");
        session_start();
        echo "<head> <link rel = \"stylesheet\" href=''>" .
            "<title> NetVOD </title></head>";
        $action = $_GET['action'] ?? "menu";

        switch ($action) {
            case "menu":
                echo "<h1> NetVOD </h1>";
        }

    }
}