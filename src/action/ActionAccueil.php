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
        $series_encours = Repository::getInstance()->getSeriesEncours($user_id);
        $rendererA = new ListeSerieRenderer($series_pref);
        $rendererB = new ListeSerieRenderer($series_encours);

        if (sizeof($series_pref) === 0) {
            $html = "<h1>Bienvenue sur votre page d'accueil, {$pseudo} !</h1>
                    <p>Vous n'avez pas de séries dans votre liste de préférences</p>";
        } else {
            $html = "<h1>Bienvenue sur votre page d'accueil, {$pseudo} !</h1>
                    <p>Les séries que vous avez le plus aimé : </p>" . $rendererA->render();
        }

        if (sizeof($series_encours) === 0) {
            $html .= "<p>Vous n'avez pas de séries en cours.</p>";
        } else {
            $html .= "<p>Vos séries en cours : </p>" . $rendererB->render();
        }

        return $html;
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}