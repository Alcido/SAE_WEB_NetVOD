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
        //$serie = $repo->getSerie($_GET['serieID']);
        $serie = new Serie("hizsd",22,[],"hizsd","hizsd");

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