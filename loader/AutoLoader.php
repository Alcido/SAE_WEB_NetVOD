<?php

namespace NetVOD\loader;

class AutoLoader
{
    private String $namespacep;
    private String $path;
    public function __construct(String $namespacep, String $path){
        $this->namespacep = $namespacep;
        $this->path = $path;
    }

    public function register(){
        spl_autoload_register([$this, "loadClass"]);
    }

    /**
     * @throws \Exception
     */
    public function loadClass(String $class){
        $pathh = explode("\\", $class);
        $pathh = array_slice($pathh, 2, count($pathh)-1);
        $pathh = implode("/", $pathh);
        $class = $this->path . "/" . $pathh . ".php";
        if (file_exists($class)) {
            require_once $class;
        }
        else throw new \Exception("Class not found : " . $class);
    }
}