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

        //on récupère les différentes séries
        $series_pref = Repository::getInstance()->getPref($user_id);
        $series_encours = Repository::getInstance()->getSeriesEncours($user_id);
        $series_dejaVu = Repository::getInstance()->getSeriesDejaVu($user_id);

        //récupère les renderers
        $rendererA = new ListeSerieRenderer($series_pref);
        $rendererB = new ListeSerieRenderer($series_encours);
        $rendererC = new ListeSerieRenderer($series_dejaVu);

        $html = "<h1>Bienvenue sur votre page d'accueil, {$pseudo} !</h1>";

        if (sizeof($series_pref) === 0) {
            $html .= "<p>Vous n'avez pas de séries dans votre liste de préférences</p>";
        } else {
            $html .= "<div class='series-liste'><p>Les séries que vous avez le plus aimé : </p>" . $rendererA->render() . "</div>";
        }

        if (sizeof($series_encours) === 0) {
            $html .= "<p>Vous n'avez pas de séries en cours.</p>";
        } else {
            $html .= "<div class='series-liste'><p>Vos séries en cours : </p>" . $rendererB->render() . "</div>";
        }

        if (sizeof($series_dejaVu) === 0 ) {
            $html .= "<p>Vous n'avez pas de séries finies.</p>";
        } else {
            $html .= "<div class='series-liste'><p>Vos séries en terminée(s) : </p>" . $rendererC->render() . "</div>";
        }

        return $html;
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}