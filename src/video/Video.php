<?php

namespace NetVOD\src\video;

class Video
{
    // Attributs
    private string $nom;
    private int $id;
    private string $lienFichier;
    private ?string $lienImage;
    private int $duree;
    private int $annee;
    private string $resume;

    /**
     * @param string $nom nom de la video
     * @param string $fichier lien vers le fichier de la video
     * @param int $duree duree de la video
     * @param int $annee annee de la video
     * @param string $resume resume de la vidéo
     */
    public function __construct(int $id, string $nom, string $fichier, int $duree, int $annee, string $resume, string $lienImage = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->lienFichier = $fichier;
        $this->lienImage = $lienImage;
        $this->duree = $duree;
        $this->annee = $annee;
        $this->resume = $resume;
    }

    /** Getter magique
     * @param string $attribut
     * @return mixed valeur de l'attribut
     * @throws \Exception si l'attribut n'existe pas
     */
    public function __get(string $attribut) : mixed
    {
        if (property_exists($this, $attribut)) return $this->$attribut;
        throw new \Exception("attribut non defini : $attribut");
    }
}