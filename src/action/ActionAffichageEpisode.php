<?php

namespace NetVOD\src\action;

use NetVOD\src\action\Action;
use NetVOD\src\renderer\EpisodeRenderer;
use NetVOD\src\repository\Repository;

class ActionAffichageEpisode extends Action
{

    /**
     * @inheritDoc
     */
    public function lancerGet(): string
    {
        $repo = Repository::getInstance();

        //on récupère l'épisode
        $ep = $repo->getEpisode($_GET['episodeID']);

        if ($ep == null){
            return "<h1> episode not existing </h1>";
        }else {
            $episodeNum = $ep->numero;
            $ajout = $repo->addSerieEnCours($ep->serie_id, unserialize($_SESSION['user'])->id, $episodeNum);
            if (!$ajout) {
                throw new \Exception("Erreur dans l'ajout en court");
            }

            $tmp = "<div>" . (new EpisodeRenderer($ep))->renderLong() . "</div>";

            return $tmp;
        }

    }

    /**
     * @inheritDoc
     */
    public function lancerPost(): string
    {
        $tmp = "";
        if(isset($_POST['note'])){
            $repo = Repository::getInstance();
            $episode = $repo->getEpisode($_GET['episodeID']);
            $_POST['commentaire'] = htmlspecialchars($_POST['commentaire']  ?? '', ENT_QUOTES, 'UTF-8');
            $repo->noterSerie($episode->serie_id,unserialize($_SESSION['user'])->id,intval($_POST['note']),$_POST['commentaire']);
            $tmp = "<h2> Note appliquee</h2>";
        }
        //TODO Save TIMECODE
        return $tmp . $this->lancerGet();
    }
}