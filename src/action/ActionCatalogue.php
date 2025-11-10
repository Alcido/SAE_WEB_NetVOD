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

        $catalogue = $repo->getCatalogue();
        $_SESSION['catalogue'] = serialize($catalogue);

        //récupération des genres, public
        $type_genre = Repository::getInstance()->getGenre();
        $type_public = Repository::getInstance()->getTypePublic();
        //tableau des types de tri
        $type_tris = ["moyenne", "nbEpisodes", "dateAjout", "A-Z"];

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
        <a href="?action=catalogue"><button>Réinitialiser les filtres</button></a>
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