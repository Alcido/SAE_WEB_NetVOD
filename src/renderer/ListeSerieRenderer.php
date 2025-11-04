<?php

namespace NetVOD\src\renderer;

class ListeSerieRenderer
{

    private array $series;

    public function __construct(array $series) {
        $this->series = $series;
    }

    public function render() : string {
        $nbSerie = sizeof($this->series);
        $html = "<p>Nombre de séries dans cette liste : $nbSerie</p>";
        $html .= "<ul>";
        foreach ($this->series as $serie) {
            $rendererSerie = new SerieRenderer($serie);
            $html .= "<li>" . $rendererSerie->renderCompact() . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}