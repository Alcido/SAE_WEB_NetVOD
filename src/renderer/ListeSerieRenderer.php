<?php

namespace NetVOD\src\renderer;

class ListeSerieRenderer
{

    private array $series;

    public function __construct(array $series) {
        $this->series = $series;
    }

    public function render(): string {
        $nbSerie = sizeof($this->series);

        // En-tête du catalogue
        $html = "<p>Nombre de séries dans cette liste : $nbSerie</p>";

        // Container en grille
        $html .= "<div class='catalogue-container'>";

        // Boucle sur chaque série
        foreach ($this->series as $serie) {
            $rendererSerie = new SerieRenderer($serie);
            // On suppose que renderCompact() renvoie déjà une carte HTML d'une série
            $html .= "<div class='serie-card'>" . $rendererSerie->renderCompact() . "</div>";
        }

        $html .= "</div>"; // fin du container

        return $html;
    }

}