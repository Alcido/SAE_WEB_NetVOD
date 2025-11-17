<?php
declare(strict_types=1);

namespace NetVOD\src\renderer;
use NetVOD\src\repository\Repository;
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
        <a href="?action=episode&episodeNum={$this->video->numero}&episodeID={$this->video->id}">
        <img src="{$this->video->lienImage}" alt="Image de l'épisode"/>
        <p>Episode n°{$this->video->numero} : {$this->video->nom}</p>
        <p>Durée : {$this->video->duree}</p></a></div>
HTML;


        return $html;
    }

    /**Renderer long d'une vidéo
     * @return string affichage complet de la vidéo
     */
    public function renderLong(): string {
        $nextEpisode = Repository::getInstance()->getNextEpisode($this->video->id);

        // Préparer le bouton seulement si un épisode suivant existe
        if ($nextEpisode) {
            $nextButton = "<button class='nextEp' onclick=\"window.location.href='?action=episode&episodeNum={$nextEpisode->numero}&episodeID={$nextEpisode->id}'\">Next Episode</button>";
        } else {
            $nextButton = "<button class='nextEp' disabled>Next Episode</button>";
        }

        $html = <<<HTML
                <video controls width="250">
                    <source src="video/{$this->video->lienFichier}" type="video/mp4" />
                </video>
                $nextButton
                
                <p>Série : {$this->video->nom_serie} - ep. {$this->video->numero} : {$this->video->nom}</p>
                <p>Résumé : {$this->video->resume}</p>
                <p>Durée : {$this->video->duree}s</p>
                <br><br>
                <form action="?action=episode&episodeID={$this->video->id}" method="post">
                    <input type="text" name="commentaire" placeholder="Ajouter un commentaire">
                    <input type="radio" id="num1" name="note" value="1">
                    <label for="num1">1</label><br>
                      
                    <input type="radio" id="num2" name="note" value="2">
                    <label for="num2">2</label><br>
                    
                    <input type="radio" id="num3" name="note" value="3">
                    <label for="num3">3</label><br>
                      
                    <input type="radio" id="num4" name="note" value="4">
                    <label for="num4">4</label><br>
                      
                    <input type="radio" id="num5" name="note" value="5">
                    <label for="num5">5</label><br>
                    <input type="submit" name="addComment" value="Ajouter un commentaire">
                </form>
                HTML;

        return $html;
    }

}