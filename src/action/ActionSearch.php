<?php

namespace NetVOD\src\action;

use NetVOD\src\action\Action;
use NetVOD\src\renderer\ListeSerieRenderer;
use NetVOD\src\repository\Repository;

class ActionSearch extends Action
{

    /**
     * @inheritDoc
     */
    public function lancerGet(): string
    {
        $repo = Repository::getInstance();

        if (isset($_GET['search'])){
            $listeSeries = $repo->getSerieRecherche($_GET['search']);
            if ($listeSeries == null){
                $tmp = "<h1>Aucun résultat.</h1>";
            }else{
                $tmp = (new ListeSerieRenderer($listeSeries))->render();
            }
        }

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