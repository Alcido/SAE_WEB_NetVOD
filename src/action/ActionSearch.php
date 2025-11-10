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

        $tmp = <<<HTML
        <h1>Rechercher une série</h1>
            <form action="?action=search" method="get">
            <input type="hidden" name="action" value="search">
            <input type="text" name="search" id="search" placeholder="Rechercher une série" required autofocus>
            <input type="submit" value="Search">
            </form>
        
        
        HTML;

        if (isset($_GET['search'])){
            $listeSeries = $repo->getSerieRecherche($_GET['search']);
            if ($listeSeries == null){
                $tmp .= "<h1>Aucun résultat.</h1>";
            }else{
                $tmp .= (new ListeSerieRenderer($listeSeries))->render();
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