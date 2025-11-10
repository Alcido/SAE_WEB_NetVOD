<?php

namespace NetVOD\src\action;

class ActionDefault extends Action {

    public function lancerGet(): string
    {
        return <<<HTML
<h1>Bienvenue sur NetVOD !</h1>
<p>Profitez de notre catalogue des séries les plus en vogues !</p>
<p>N'hésitez pas à vous rendre sur votre accueil où vous trouverez vos séries préférées, vos séries en cours ainsi que vos séries terminées !</p>
HTML;

    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }



}