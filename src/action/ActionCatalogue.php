<?php

namespace NetVOD\src\action;

use NetVOD\src\renderer\ListeSerieRenderer;
use NetVOD\src\repository\Repository;

class ActionCatalogue extends Action
{
    /** Méthode du lancement du GET
     * @return string affichage du catalogue en HTML
     * @throws \Exception
     */
    public function lancerGet(): string
    {
        $catalogue = Repository::getInstance()->getCatalogue();
        $renderer = new ListeSerieRenderer($catalogue);
        return "<h1>Catalogue de NetVOD</h1>" . $renderer->render();
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}