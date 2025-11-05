<?php

namespace NetVOD\src\video;

class Episode extends Video
{
    private int $serie_id;
    private string $nom_serie;
    private int $numero;

    /** Constructeur de la classe Episode
     * @param string $nom
     * @param string $fichier
     * @param int $duree

     * @param string $nom_serie
     * @param string|null $lienImage
     * @param string $resume
     */
    public function __construct(int $id_ep,int $serie_id, string $nom, string $fichier, int $duree, string $nom_serie, int $numero, string $resume, ?string $lienImage = null)
    {
        parent::__construct($id_ep, $nom, $fichier, $duree, $resume, $lienImage);
        $this->serie_id = $serie_id;
        $this->nom_serie = $nom_serie;
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