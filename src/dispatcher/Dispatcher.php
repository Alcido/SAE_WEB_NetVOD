<?php

namespace NetVOD\src\dispatcher;

use NetVOD\src\repository\Repository;

class Dispatcher
{
    /**
     * @throws \Exception
     */
    public static function run() : void{
        Repository::setConfig("/opt/lampp/htdocs/Config.ini");
        echo "<head> <link rel = \"stylesheet\" href=''>" .
            "<title> NetVOD </title></head>";
        $action = $_GET['action'] ?? "menu";

        switch ($action) {
            case "menu":
                echo "<h1> NetVOD </h1>";
        }

    }
}