<?php

namespace NetVOD\src\video;

class Episode extends Video
{
    private int $serie_id;
    private int $numero;

    /** Constructeur de la classe Episode
     * @param string $nom
     * @param string $fichier
     * @param int $duree
     * @param int $annee
     * @param int $serie
     * @param string|null $lienImage
     * @param string $resume
     */
    public function __construct(string $nom, string $fichier, int $duree, int $annee, int $serie, int $numero, string $resume, ?string $lienImage = null)
    {
        parent::__construct($nom, $fichier, $duree, $annee, $resume, $lienImage);
        $this->serie_id = $serie;
        $this->numero = $numero;
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