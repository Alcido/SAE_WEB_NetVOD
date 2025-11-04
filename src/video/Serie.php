<?php

namespace NetVOD\src\video;

class Serie
{
    private string $nom;
    private ?string $genre;
    private ?string $public;
    private int $annee;
    private string $date_ajout;
    private array $episodes;
    private ?string $lienImage;
    private string $descriptif;

    /** Constructeur de la classe Serie
     * @param string $nom nom de la série
     * @param string $genre genre de la série
     * @param int $annee année de la série
     * @param array $episodes liste d'épisodes
     */
    public function __construct(string $nom, int $annee, array $episodes, string $desc, string $dateAj, string $genre = null, string $public = null, string $lienImage = null) {
        $this->nom = $nom;
        $this->genre = $genre;
        $this->public = $public;
        $this->annee = $annee;
        $this->episodes = $episodes;
        $this->lienImage = $lienImage;
        $this->descriptif = $desc;
        $this->date_ajout = $dateAj;
    }

    /** Getter magique
     * @param $attribute
     * @return mixed
     */
    public function __get($attribute) : mixed
    {
        if (property_exists($this, $attribute)) return $this->$attribute;
        return null;
    }
}