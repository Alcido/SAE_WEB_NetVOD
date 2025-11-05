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
        $episodeNum = intval($_GET['episodeNum']);
        $episodeID = intval($_GET['episodeID']);
        if (!isset($episodeID)) {
            return "<h1> no episode selected </h1>";
        }else if ($repo->getEpisode($episodeID) == null){
            return "<h1> episode not existing </h1>";
        }else {
            $episode = $repo->getEpisode($episodeID);
            $ajout = $repo->addSerieEnCours($episode->serie_id, unserialize($_SESSION['user'])->id, $episodeNum);
            if (!$ajout) {
                throw new \Exception("Erreur dans l'ajout en court");
            }
            $tmp = "<div>";
            $tmp .= (new EpisodeRenderer($episode))->renderLong();
            $tmp .= "</div>";
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
            $repo->noterSerie($episode->serie_id,unserialize($_SESSION['user'])->id,intval($_POST['note']),$_POST['commentaire']);
            $tmp = "<h2> Note appliquee</h2>";
        }
        //TODO Save TIMECODE
        return $tmp . $this->lancerGet();
    }
}