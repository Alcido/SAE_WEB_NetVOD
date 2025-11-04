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
        if (!isset($_GET['episodeID'])) {
            return "<h1> no episode selected </h1>";
        }else if ($repo->getEpisode($_GET['episodeID']) == null){
            return "<h1> episode not existing </h1>";
        }else {
            $tmp = "<div>";
            $tmp .= (new EpisodeRenderer($_GET['episodeID']))->renderLong();
            $tmp .= "</div>";
            return $tmp;
        }

    }

    /**
     * @inheritDoc
     */
    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}