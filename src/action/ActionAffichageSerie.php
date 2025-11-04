<?php

namespace NetVOD\src\action;

use NetVOD\src\renderer\SerieRenderer;
use NetVOD\src\repository\Repository;
use NetVOD\src\video\Serie;

class ActionAffichageSerie extends Action
{

    /**
     * @inheritDoc
     */
    public function lancerGet(): string
    {
        $repo = Repository::getInstance();
        if (!isset($_GET['serieID'])) {
            return "<h1> no serie selected </h1>";
        }
        $serie = $repo->getSerie($_GET['serieID']);
        if ($serie == null) {
            return "<h1> serie not existing </h1>";
        }
        $tmp ="<div>";
        $tmp .= (new SerieRenderer($serie))->renderLong();
        $tmp .= "</div>";


        return $tmp;

    }

    /**
     * @inheritDoc
     */
    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}