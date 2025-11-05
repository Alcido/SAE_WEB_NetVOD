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
        $repo = Repository::getInstance();

        //Preparation du catalogue
        if (isset($_GET['tri'])&& isset($GET['filtre'])) {
            $catalogue = $repo->getCatalogueTriFiltre($_GET['filtre'],$_GET['valeurFiltre'],$_GET['tri']);
        }elseif (isset($_GET['tri'])) {
            echo "test : $_GET[tri]";
            $catalogue = $repo->getCatalogueTri($_GET['tri']);
        }elseif (isset($_GET['filtre'])) {
            $catalogue = $repo->getCatalogueFiltre($_GET['filtre'],$_GET['valeurFiltre']);
        }else{
            $catalogue = $repo->getCatalogue();
        }


        if ($catalogue == null) {
            $catalogue = [];
        }

        $renderer = new ListeSerieRenderer($catalogue);

        //Recupere les argument de tri
        if (isset($_GET['tri'])) {
            $tri = <<<HTML
                 <input type="hidden" name="tri" value="$_GET[tri]"> 
            HTML;
        }else {
            $tri = "";
        }

        //Recupere les argument de filtre
        if (isset($_GET['filtre'])) {
            $filtre = <<<HTML
                <input type="hidden" name="filtre" value="$_GET[filtre]"> 
                <input type="hidden" name="valeurFiltre" value="$_GET[valeurFiltre]"> 
            HTML;
        }else{
            $filtre = "";
        }

        //Formulaire de tri et de filtre
        $tmp = <<<HTML
            <form action="" method="get">
                <input type="hidden" name="action" value="catalogue">
                $filtre
                <button type="submit" name="tri" value="moyenne">Moyenne</button>
                <button type="submit" name="tri" value="titre">A-Z</button>
            </form>

            <form action="" method="get">
                <input type="hidden" name="action" value="catalogue">
                $tri
                <button type="submit" name="filtre" value="genre">Genre</button>
                <button type="submit" name="filtre" value="public">Public</button>
                <input type="text" name="valeurFiltre" placeholder="Valeur" required>
            </form>

            <a href="?action=catalogue"><button>Reset</button></a>
        HTML;


        $tmp .= "<h1>Catalogue de NetVOD</h1>" . $renderer->render();

        return $tmp;
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}