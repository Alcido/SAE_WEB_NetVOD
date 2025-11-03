<?php

namespace NetVOD\src\video;

class ListeSerie
{
    private array $series;

    /** Constructeur de la classe ListeSerie
     * @param array $series
     */
    public function __construct(array $series)
    {
        $this->series = $series;
    }

    public function __get($attribute) : mixed
    {
        if (property_exists($this, $attribute)) return $this->$attribute;
        return null;
    }
}