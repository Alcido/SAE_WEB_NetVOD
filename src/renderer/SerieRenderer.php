<?php

namespace NetVOD\src\renderer;

use NetVOD\src\repository\Repository;
use NetVOD\src\video\Serie;

class SerieRenderer
{

    private Serie $serie;

    public function __construct(Serie $serie) {
        $this->serie = $serie;
    }

    /**Render d'une série
     * @return string affichage de la série en court
     */
    public function renderCompact() : string {
        $html = <<<HTML
        <div class="serie-card">
            <a href='?action=serie&serieID={$this->serie->id}'>
                <img src="{$this->serie->lienImage}" />
                <p>{$this->serie->nom}</p>
                <p>{$this->serie->genre}</p>
            </a>
            {$this->favorite()}
        </div>
HTML;
        return $html;
    }

    /**Renderer long d'une série
     * @return string affichage complet de la série
     */
    public  function renderLong() : string {

        $nbEpisodes = sizeof($this->serie->episodes);

        $html = "<div class=\"serie-details\"><a href='?action=serie&serieID={$this->serie->id}'><p>Titre de la série : {$this->serie->nom}</p>
                 <p>Genre : {$this->serie->genre}</p>
                 <p>Descriptif : {$this->serie->descriptif}</p>
                 <p>Nombre d'épisodes : $nbEpisodes</p>
                 <p>Date d'ajout : {$this->serie->date_ajout}, date de création : {$this->serie->annee}</p>
                 <p>Liste des épisodes : </p><ul>";

        foreach ($this->serie->episodes as $episode) {
            $episodeRenderer = new EpisodeRenderer($episode);
            $html .= "<li>" . $episodeRenderer->renderCompact() . "</li>";
        }
        $html .= "</ul></a></div>";
        $html .= $this->favorite();

        return $html;
    }

    private function favorite() : string {
        $repo = Repository::getInstance();
        $pref = $repo->getPref(unserialize($_SESSION['user'])->id);
        if (in_array($this->serie,$pref)){
            $isLiked = "remove";
            $texte="Supprimer des favoris";
        }else{
            $isLiked = "add";
            $texte="Ajouter des favoris";
        }

        $html = <<<HMTL
            <form class="$isLiked" action="?action=add-pref&serie_id={$this->serie->id}" method="post">
                <button type="submit" name="addFavorite" value="$isLiked">$texte</button>
            </form>
HMTL;

        return $html;
    }
}