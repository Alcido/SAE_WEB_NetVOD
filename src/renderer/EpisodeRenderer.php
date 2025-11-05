<?php
declare(strict_types=1);

namespace NetVOD\src\renderer;
use NetVOD\src\video\Episode;
use NetVOD\src\video\Video;

class EpisodeRenderer
{
    private Episode  $video;

    /**Constructeur du renderer
     * @param Video $video
     */
    public function __construct(Episode $video) {
        $this->video = $video;
    }

    /**Render compact d'une vidéo
     * @return string affichage de la vidéo
     */
    public function renderCompact() : string {
        $html = <<<HTML
        <div class="serie-card">
        <a href="?action=episode&episodeID={$this->video->id}">
        <img src="{$this->video->lienImage}" alt="Image de l'épisode"/>
        <p>Episode n°{$this->video->numero} : {$this->video->nom}</p>
        <p>Durée : {$this->video->duree}</p></a></div>
HTML;


        return $html;
    }

    /**Renderer long d'une vidéo
     * @return string affichage complet de la vidéo
     */
    public  function renderLong() : string {
        $html = <<<HMTL
        <video controls width="250">
            <source src="video/{$this->video->lienFichier}" type="video/mp4" />
        </video>
        <p>Série : {$this->video->nom_serie} - ep. {$this->video->numero} : {$this->video->nom}</p>
        <p>Résumé : {$this->video->resume}</p>
        <p>Durée : {$this->video->duree}</p>
        </br>
        </br>
        <form action="?action=episode&episodeID={$this->video->id}" method="post">
            <input type="text" name="commentaire" placeholder="Ajouter un commentaire">
            <input type="number" name="note" placeholder="Note">
            <input type="submit" name="addComment" value="Ajouter un commentaire">
        </form>
        HMTL;
        return $html;
    }
}