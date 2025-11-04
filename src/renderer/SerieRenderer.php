<?php

namespace NetVOD\src\renderer;

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


        $html = <<<HMTL
        <img src="{$this->serie->lienImage}" />
        <p>Titre de la série : {$this->serie->nom}</p>
        <p>Genre : {$this->serie->genre}</p>
    
HMTL;
        return $html;
    }

    /**Renderer long d'une série
     * @return string affichage complet de la série
     */
    public  function renderLong() : string {
        $nbEpisodes = sizeof($this->serie->episodes);

        $html = "<p>Titre de la série : {$this->serie->nom}</p>
                 <p>Genre : {$this->serie->genre}</p>
                 <p>Descriptif : {$this->serie->descriptif}</p>
                 <p>Nombre d'épisodes : $nbEpisodes</p>
                 <p>Date d'ajout : {$this->serie->date_ajout}, date de création : {$this->serie->annee}</p>
                 <p>Liste des épisodes : </p><ul>";

        foreach ($this->serie->episodes as $episode) {
            $html .= "<li>" . new EpisodeRenderer($episode)->renderCompact() . "</li>";
        }
        $html .= "</ul>";

        return $html;
    }
}