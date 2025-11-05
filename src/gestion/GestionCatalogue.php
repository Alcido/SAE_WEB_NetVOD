<?php

namespace NetVOD\src\gestion;

use NetVOD\src\repository\Repository;

class GestionCatalogue
{

    /**Méthode permettant d'avoir l'affichage html des options de genre
     * @param array $array liste des genres de la base
     * @return string affichage html
     */
    public static function getGenreHTML(array $array) : string {

        $html = '<option value="" ' . (empty($_GET['genre']) ? 'selected' : '') . '>Tous</option>';
        foreach ($array as $genre) {
            $html .= '<option value="' . $genre . '" ' .
                (($_GET['genre'] ?? '') == $genre ? 'selected' : '') . '>' .
                $genre . '</option>';
        }

        return '<form action="?action=catalogue" method="GET">
          <label>Genre :</label>
          <select name="genre" onchange="this.form.submit()">'.
            $html
            . '</select>
        
          <input type="hidden" name="tri" value="' . ($_GET['tri'] ?? '') . '">
          <input type="hidden" name="public" value="' . ($_GET['public'] ?? '') . '">
        </form>';
    }

    /**Méthode permettant d'afficher la liste des publics possible de la base
     * @param array $array liste des tri possible
     * @return string affichage des choix de public
     */
    public static function getPublicHTML(array $array) : string {

        $html = '<option value="" ' . (empty($_GET['public']) ? 'selected' : '') . '>---</option>';
        foreach ($array as $public) {
            $html .= '<option value="' . $public . '" ' .
                (($_GET['tri'] ?? '') == $public ? 'selected' : '') . '>' .
                $public . '</option>';
        }

        return '<form action="?action=catalogue" method="GET">
          <label>Public :</label>
          <select name="public" onchange="this.form.submit()">' .
            $html
          . '</select>
        
          <input type="hidden" name="tri" value="' . ($_GET['tri'] ?? '') . '">
          <input type="hidden" name="genre" value="' . ($_GET['genre'] ?? '') . '">
        </form>';
    }

    /**Méthode permettant d'afficher la liste des tri possibles de la liste
     * @param array $array liste des tri possibles
     * @return string affichage des choix de tri
     */
    public static function getTriHTML(array $array) : string {
        $html = '<option value="" ' . (empty($_GET['tri']) ? 'selected' : '') . '>---</option>';
        foreach ($array as $tri) {
            $html .= '<option value="' . $tri . '" ' .
                (($_GET['tri'] ?? '') == $tri ? 'selected' : '') . '>' .
                $tri . '</option>';
        }
        return '<form action="?action=catalogue" method="GET">
          <label>Trier par :</label>
          <select name="tri" onchange="this.form.submit()">' .
           $html
          . '</select>
        
          <input type="hidden" name="genre" value="' . ($_GET['genre'] ?? '') . '">
          <input type="hidden" name="public" value="' . ($_GET['public'] ?? '') . '">
        </form>';
    }
//    le tableau devrait être un truc du style ["moyenne", "nbEpisodes", "dateAjout"]


    /**Méthode permettant de retourner la liste passée en parametre triée selon un parametre
     * @param array $array liste à trier
     * @param string $typeTri type de tri choisi
     * @return array liste triée
     * @throws \Exception au cas ou erreur dans le choix du tri
     */
    public static function trierListe(array $array, string $typeTri) : void {
        switch($typeTri) {
            case 'moyenne':
                self::trierMoyenne($array);
                break;
            case 'nbEpisodes':
                self::trierNbEpisode($array);
                break;
            case 'dateAjout':
                self::trierDateAjout($array);
                break;
            case '':
                //rien ne se passe la liste est pas modifiée
                break;
            default:
                throw new \Exception("Erreur dans le choix du tri");
        }
    }

    /**Tri par moyenne
     * @param array $array liste à trier
     * @return true liste triée
     */
    private static function trierMoyenne(array $array) : void
    {
        usort($array, function ($a, $b) {
            $noteA = Repository::getInstance()->getNoteMoyenne($a->id) ?? 0;
            $noteB = Repository::getInstance()->getNoteMoyenne($b->id) ?? 0;
            return $noteA <=> $noteB;
        });
    }

    /**Tri par nombre d'épisode
     * @param array $array liste à trier
     * @return array liste triée
     */
    private static function trierNbEpisode(array $array) : void{
        usort($array, function ($a, $b) {
            return count($a->episodes) <=> count($b->episodes);
        });
    }

    /**Tri par date d'ajout
     * @param array $array liste à trier
     * @return array liste triée
     */
    private static function trierDateAjout(array $array) : void{
        //TODO je sais pu comment les dates sont stockées
    }

    /**Permet de filter la liste sur le genre
     * @param array $array liste a filtrer
     * @param string $genre genre souhaité
     * @return array liste filtrée
     */
    public static function filtrerGenre(array $array, string $genre) : array {
        if ($genre === '') return $array;
        $res = [];
        foreach ($array as $serie) {
            if ($serie->genre === $genre) {
                $res[] = $serie;
            }
        }
        return $res;
    }

    /**Permet de filter la liste sur le public
     * @param array $array liste a filtrer
     * @param string $public public souhaité
     * @return array liste filtrée
     */
    public static function filtrerPublic(array $array, string $public) : array {
        if ($public === '') return $array;
        $res = [];
        foreach ($array as $serie) {
            if ($serie->public === $public) {
                $res[] = $serie;
            }
        }
        return $res;
    }


}