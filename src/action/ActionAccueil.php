<?php

namespace NetVOD\src\action;

use NetVOD\src\renderer\ListeSerieRenderer;
use NetVOD\src\repository\Repository;

class ActionAccueil extends Action
{
    public function lancerGet(): string
    {
        $user_id = unserialize($_SESSION['user'])->id;
        $pseudo = unserialize($_SESSION['user'])->pseudo;
        $series_pref = Repository::getInstance()->getPref($user_id);
        $renderer = new ListeSerieRenderer($series_pref);
        if (sizeof($series_pref) === 0) {
            return "<h1>Bienvenue sur votre page d'accueil, {$pseudo} !</h1>
                    <p>Vous n'avez pas de séries dans votre liste de préférences</p>";
        } else {
            return "<h1>Bienvenue sur votre page d'accueil, {$pseudo} !</h1>
                    <p>Les séries que vous avez le plus aimé : </p>" . $renderer->render();
        }
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}