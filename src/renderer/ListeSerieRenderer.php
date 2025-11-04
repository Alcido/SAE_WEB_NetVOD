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
            $html .= "<li>" . new SerieRenderer($serie)->renderCompact() . "</li>";
        }
        $html .= "</ul>";
        return $html;
    }
}