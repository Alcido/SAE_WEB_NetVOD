<?php

namespace NetVOD\src\action;

class ActionDefault extends Action {

    public function lancerGet(): string
    {
       return <<<HTML
<p>page par défaut de NetVOD, permet de voir si ça marche</p>
HTML;

    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }



}