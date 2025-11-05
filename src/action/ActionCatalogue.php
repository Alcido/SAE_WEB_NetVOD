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
        $tri = null;
        $filtre = null;
        if (isset($_GET['tri'])) {
            $_SESSION['tri'] = $_GET['tri'];
        }

        if (isset($_GET['filtre'])) {
            $filtre = $_GET['filtre'];
        }


        $catalogue = Repository::getInstance()->getCatalogue($tri,$filtre);





        $renderer = new ListeSerieRenderer($catalogue);

        $tmp = <<<HTML
            <form action="" method="get">
                <button type="submit" name="tri" value="triMoyenne">Moyenne</button>
                <button type="submit" name="tri" value="triAlphabet">A-Z</button>
            </form>
        HTML;


        return "<h1>Catalogue de NetVOD</h1>" . $renderer->render();
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}