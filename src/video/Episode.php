<?php

namespace NetVOD\src\video;

class Episode extends Video
{
    private int $serie_id;

    /** Constructeur de la classe Episode
     * @param string $nom
     * @param string $fichier
     * @param int $duree
     * @param int $annee
     * @param int $serie
     * @param string|null $lienImage
     */
    public function __construct(string $nom, string $fichier, int $duree, int $annee, int $serie, string $lienImage = null)
    {
        parent::__construct($nom, $fichier, $duree, $annee, $lienImage);
        $this->serie_id = $serie;
    }


    /** Getter magique
     * @param string $attribut
     * @return mixed
     * @throws \Exception si l'attribut n'existe pas
     */
    public function __get(string $attribut) : mixed
    {
        if (property_exists($this, $attribut)) return $this->$attribut;
        throw new \Exception("attribut non defini : $attribut");
    }
}