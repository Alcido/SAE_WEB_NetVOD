<?php

namespace NetVOD\src\repository;

use Exception;
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

    public function getPDO(): PDO {
        return $this->pdo;
    }

    //permet a un user de se cree un compte renvoie un boolean si reussite ou non
    public function createUser(string $pseudo, string $email, string $password) : bool {
        return false;
    }

    //verifie que le User existe renvoie true ou false
    public function isUser(string $email, string $password) : ?bool {
        return null;
    }

    //retourne l'id de l'user par son email
    public function findUserEmail(string $email) : ?string{
        return null;
    }

    //retourne un User par son id
    public function findUserID(string $user_id) : ?User{
        return null;
    }

    //retourne les infod de l'user
    public function getinfo(string $user_id) : ?User{
        return null;
    }

    //retourne le catalogue de serie
    public function getCatalogue(): ?array{
        return null;
    }

    //retourne les infos de la serie
    public function getInfosSerie(string $serie_id): ?Serie{
        return null;
    }

    //ajoute une serie
    public function addSerie(Serie $serie) : ?bool{
        return null;
    }

    //ajoute d'iun serie preferé
    public function addPref(string $serie_id):?bool{
        return null;
    }

    //ajoute serie en cours
    public function setEnCours(string $serie_id) : ?bool
    {
        return null;
    }

    //permet de note une serie
    public function noteSerie(string $serie_id,string $user_id, int $note) : ?bool
    {
        return null;
    }

    //permet de commenté une serie
    public function commSerie(string $serie_id,string $user_id, string $comment) : ?bool
    {
        return null;
    }

    //retourne la note moyenne d'une serie
    public function getNoteMoyenne(string $serie_id): ?float{
        return null;
    }

    //retpourne la lsiet des episode d'une serie
    public function getEpisodes(string $serie_id): ?array
    {
        return null;
    }

    //retourne les infos d'un episode donné
    public function getInfosEpisode(string $episode_id): ?Episode
    {
        return null;
    }

    //ajoute u episode a une serie
    public function addEpisode(string $serie_id, Episode $ep) : ?bool
    {
        return null;
    }

    //retounre preference d'un use
    public function gerpref(string $user_id) : ?array
    {
        return null;
    }


}