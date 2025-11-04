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
        $html = <<<HMTL
        <a href="?action=viewEpisode&episode=$this->video->id"<img src="{$this->video->lienImage}" />
        <p>Episode n°{$this->video->numero} : {$this->video->nom}</p>
        <p>Durée : {$this->video->duree}</p>
HMTL;

        return $html;
    }

    /**Renderer long d'une vidéo
     * @return string affichage complet de la vidéo
     */
    public  function renderLong() : string {
        $html = <<<HMTL
        <video controls width="250">
            <source src="{$this->video->lienFichier}" type="video/mp4" />
        </video>
        <p>Série : {$this->video->nom_serie} - ep. {$this->video->numero} : {$this->video->nom}</p>
        <p>Résumé : {$this->video->resume}</p>
        <p>Durée : {$this->video->duree}</p>
        <p>Année de création : {$this->video->annee}</p>
        
HMTL;
        return $html;
    }
}