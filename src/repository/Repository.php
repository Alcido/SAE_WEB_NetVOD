<?php

namespace NetVOD\src\repository;

use Exception;
use NetVOD\src\auth\User;
use NetVOD\src\video\Episode;
use NetVOD\src\video\Serie;
use PDO;
use PDOException;

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
            PDO::ATTR_EMULATE_PREPARES => false ]);// empêche certaines injections SQL;
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

    /**
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @param int $role
     * @return bool
     * cette methode permet d'insérer un User dans la BD
     */
    public function createUser(string $pseudo, string $email, string $password, int $role=1) : bool {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateur (pseudo, email, password, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$pseudo,$email, $password,$role]);
    }

    /**
     * @param int $user_id
     * @return User|null
     */
    public function getUser(int $user_id) : ?User{
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                intval($data['id']),
                intval($data['role']),
                strval($data['pseudo'])
            );
        }

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


    /**
     * @param int $serie_id
     * @param int $user_id
     * @param int $note
     * @param string $comm
     * @return bool|null
     * permet de note une serie
     */
    public function noterSerie(int $serie_id,int $user_id, int $note, string $comm) : ?bool
    {
        //on vérifie que le user na pas deja noter la serie aec une exception car si l'user veut renoter mySQL nous renvoie une contrainte d'integrité du a la clé primaire composite
        try {
            $stmt = $this->pdo->prepare("INSERT INTO notation (id_user, id_serie, date_comm, commentaire, note) VALUES (?,?,?,?,?)");
            return $stmt->execute([$user_id, $serie_id, $comm, $comm, $note]);
        }catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                //le user avez deja noté la serie
                return false;
            }
            throw $e;
        }
    }



    /**
     * @param int $serie_id
     * @return float|null
     * retourne la note moyenne d'une serie
     */
    public function getNoteMoyenne(int $serie_id): ?float{
        $stmt = $this->pdo->prepare("SELECT Avg(note) FROM notation WHERE id_serie = ? group by id_serie");
        $stmt->execute([$serie_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return floatval($data['Avg(note)']);
        }
        return null;
    }

    /**
     * @param int $serie_id
     * @return array|null
     * retourne la liste des episode d'une serie
     */
    public function getListeEpisodes(int $serie_id): ?array
    {
        $stmt=$this->pdo->prepare("SELECT * FROM episode WHERE serie_id=? order by numero");
        $stmt->execute([$serie_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            return $data;
        }
        return null;
    }

    //retourne les infos d'un episode donné
    public function getEpisode(int $episode_id): ?Episode
    {
        $stmt=$this->pdo->prepare("SELECT episode.titre, episode.file, episode.duree, serie.titre as serieTitre, episode.numero,episode.resume, episode.img 
                                            FROM episode 
                                            inner Join serie on serie.id=episode.serie_id 
                                            WHERE episode.id=?");
        $stmt->execute([$episode_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Episode(
                intval($data['titre']),
                intval($data['file']),
                intval($data['duree']),
                intval($data['serieTitre']),
                intval($data['numero']),
                intval($data['resume']),
                intval($data['img'])
            );
        }
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
        $stmt=$this->pdo->prepare("SELECT commentaire FROM notation WHERE id_serie=?");
        $stmt->execute([$serie_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            return $data;
        }
        return null;
    }


}