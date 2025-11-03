<?php

namespace NetVOD\src\dispatcher;

use NetVOD\src\action\ActionRegister;
use NetVOD\src\repository\Repository;

class Dispatcher
{
    /**
     * @throws \Exception
     */

    private string $action;

    public function __construct(string $action) {
        $this->action = $action;
    }


    public function run() : void {
        Repository::setConfig("/opt/lampp/htdocs/Config.ini");
        echo "<head> <link rel = \"stylesheet\" href=''>" .
            "<title> NetVOD </title></head>";

        switch ($this->action) {
            case "menu":
                echo "<h1> NetVOD </h1>";
                break;
            default:
        }

    }
}