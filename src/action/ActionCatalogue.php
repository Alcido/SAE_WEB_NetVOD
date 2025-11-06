<?php

namespace NetVOD\src\action;

use NetVOD\src\gestion\GestionCatalogue;
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

//        Preparation du catalogue
//        if (isset($_GET['tri'])&& isset($GET['filtre'])) {
//            $filtre = $_GET['filtre'];
//            $tri = $_GET['tri'];
//            if ($filtre != "public" && $filtre != "genre" && $tri != "id" && $tri != "titre" && $tri != "moyenne") {
//                throw new \Exception("Filtre invalide");
//            }
//            $catalogue = $repo->getCatalogueTriFiltre($_GET['filtre'],$_GET['valeurFiltre'],$_GET['tri']);
//        }elseif (isset($_GET['tri'])) {
//            $tri = $_GET['tri'];
//            if ($tri != "id" && $tri != "titre" && $tri != "moyenne") {
//                throw new \Exception("Filtre invalide");
//            }
//            echo "test : $_GET[tri]";
//            $catalogue = $repo->getCatalogueTri($_GET['tri']);
//        }elseif (isset($_GET['filtre'])) {
//            $filtre = $_GET['filtre'];
//            if ($filtre != "public" && $filtre != "genre") {
//                throw new \Exception("Filtre invalide");
//            }
//            $catalogue = $repo->getCatalogueFiltre($_GET['filtre'],$_GET['valeurFiltre']);
//        }else{
//            $catalogue = $repo->getCatalogue();
//        }
//
//        //Recupere les argument de tri
//        if (isset($_GET['tri'])) {
//            $tri = <<<HTML
//                 <input type="hidden" name="tri" value="$_GET[tri]">
//            HTML;
//        }else {
//            $tri = "";
//        }
//
//        //Recupere les argument de filtre
//        if (isset($_GET['filtre'])) {
//            $filtre = <<<HTML
//                <input type="hidden" name="filtre" value="$_GET[filtre]">
//                <input type="hidden" name="valeurFiltre" value="$_GET[valeurFiltre]">
//            HTML;
//        }else{
//            $filtre = "";
//        }

        $catalogue = $repo->getCatalogue();
        $_SESSION['catalogue'] = serialize($catalogue);

        //récupération des genres, public
        $type_genre = Repository::getInstance()->getGenre();
        $type_public = Repository::getInstance()->getTypePublic();
        //tableau des types de tri
        $type_tris = ["moyenne", "nbEpisodes", "dateAjout"];

        //récupération de l'affichage html
        $selectionGenre = GestionCatalogue::getGenreHTML($type_genre);
        $selectionPublic = GestionCatalogue::getPublicHTML($type_public);
        $selectionTri = GestionCatalogue::getTriHTML($type_tris);

        //si un choix a été fait (donc un tri, un public et un genre sont dans l'url) on modifie le catalogue en session qu'on
        if (isset($_GET['tri'])) {
            $currentCatalogue = unserialize($_SESSION['catalogue']);
            $filtre1 = GestionCatalogue::filtrerGenre($currentCatalogue,$_GET['genre']);
            $filtre2 = GestionCatalogue::filtrerPublic($filtre1,$_GET['public']);
            $tri = GestionCatalogue::trierListe($filtre2, $_GET['tri']);

            $_SESSION['catalogue'] = serialize($tri);
        }

        //Formulaire de tri et de filtre
        $html = $selectionGenre . $selectionPublic . $selectionTri . '
        <a href="?action=catalogue"><button>Reset</button></a>
        <h1>Catalogue de NetVOD</h1>';

        //on prend le renderer pour la (nouvelle) liste de série
        $rendererCatalogue = new ListeSerieRenderer(unserialize($_SESSION['catalogue']));

        return $html . $rendererCatalogue->render();
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}