<?php

namespace NetVOD\src\repository;

use Exception;
use NetVOD\src\auth\User;
use NetVOD\src\video\Episode;
use NetVOD\src\video\Serie;
use PDO;

class Repository
{
    private static ?array $config = null;
    private static ?Repository $instance = null;

    public PDO $pdo;

    private function __construct() {
        $dsn = Repository::$config['driver'] . ':host=' .Repository::$config['host'] . ';dbname=' . Repository::$config['database'];
        $this->pdo = new PDO($dsn, Repository::$config['username'], Repository::$config['password'],[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // affiche les erreurs SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // renvoie des tableaux associatifs
            PDO::ATTR_EMULATE_PREPARES => false ]);// empêche certaines injections SQL);
    }

    /**
     * @throws Exception
     */
    public static function setConfig (String $file) : void {
        if (file_exists($file)) {
            Repository::$config = parse_ini_file($file);
        }else{
            throw new Exception("Config file not found");
        }
    }

    /**
     * @throws Exception
     */
    public static function getInstance() : Repository {
        if (Repository::$config == null) {
            throw new Exception("Config file not found");
        }
        else {
            if (Repository::$instance == null) {
                Repository::$instance = new Repository();
            }
            return Repository::$instance;
        }
    }


    //permet a un user de se cree un compte renvoie un boolean si reussite ou non
    public function createUser(string $pseudo, string $email, string $password) : bool {
        return false;
    }


    //retourne les infod de l'user
    public function getUser(int $user_id) : ?User{
        return null;
    }

    //retourne le catalogue de serie
    public function getCatalogue(): ?array{
        return null;
    }

    //retourne les infos de la serie
    public function getSerie(int $serie_id): ?Serie{
        return null;
    }

    //ajoute une serie
    public function addSerie(Serie $serie) : ?bool {
        return null;
    }

    //ajoute d'iun serie preferé
    public function addSeriePref(int $serie_id):?bool{
        return null;
    }

    //ajoute serie en cours
    public function addSerieEnCours(int $serie_id) : ?bool
    {
        return null;
    }

    //permet de note une serie
    public function noterSerie(int $serie_id,int $user_id, int $note, string $comm) : ?bool
    {
        return null;
    }

    //retourne la note moyenne d'une serie
    public function getNoteMoyenne(int $serie_id): ?float{
        return null;
    }

    //retourne la liste des episode d'une serie
    public function getListeEpisodes(int $serie_id): ?array
    {
        return null;
    }

    //retourne les infos d'un episode donné
    public function getEpisode(int $episode_id): ?Episode
    {
        return null;
    }

    //ajoute u episode a une serie
    public function addEpisode(int $serie_id, Episode $ep) : ?bool
    {
        return null;
    }

    //retourne les preferences d'un user
    public function getPref(int $user_id) : ?array
    {
        return null;
    }

    /**
     * @param int $serie_id id de la série dont on veut les commentaire
     * @return array|null liste des commentaires
     */
    public function getListeCommentaires(int $serie_id): ?array{
        return null;
    }


}