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
    public function getUser(string $mail) : ?array{
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$mail]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return ['user' => new User(
                intval($data['id']),
                intval($data['role']),
                strval($data['pseudo'])
            ),'pwd'=>$data['password']];
        }

        return null;
    }

    /**Méthode permettant de récupérer la liste des séries de la base
     * @return array|null liste de toutes les séries de la base de donnée
     */
    public function getCatalogue(): ?array{
        $query = "select id from serie";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $res = [];
        while ($row = $stmt->fetch()) {
            $serie = $this->getSerie($row["id"]);
            $res[] = $serie;
        }
        return $res;
    }

    /**Méthode permettant de récupérer l'objet série
     * @param int $serie_id id de la série cherchée
     * @return Serie|null
     */
    public function getSerie(int $serie_id): ?Serie{
        $query = "select id, titre, genre, type_public, annee, date_ajout, img, descriptif from serie where id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array($serie_id));
        $res = $stmt->fetch();
        $episodes = $this->getListeEpisodes($serie_id);
        return new Serie($res['id'], $res['titre'], intval($res['annee']),$episodes, $res['descriptif'], $res['date_ajout'], $res['genre'], $res['type_public'], $res['img']);
    }

    //ajoute une serie
    public function addSerie(Serie $serie) : ?bool {
        return null;
    }

    //ajoute d'iun serie preferé
    public function addSeriePref(int $serie_id, int $user_id):?bool{
        try {
            $query = "insert into prefSerie2User values (?, ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$user_id, $serie_id]);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    //ajoute serie en cours
    public function addSerieEnCours(int $serie_id) : ?bool
    {
        return true;
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
            $stmt = $this->pdo->prepare("INSERT INTO notation (id_user, id_serie, date_comm, commentaire, note) VALUES (?,?,sysdate(),?,?)");
            return $stmt->execute([$user_id, $serie_id, $comm, $note]);
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
        $stmt=$this->pdo->prepare("SELECT id FROM episode WHERE serie_id=? order by numero");
        $stmt->execute([$serie_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($data as $episode) {
            $res[] = $this->getEpisode($episode['id']);
        }
        return $res;
    }

    //retourne les infos d'un episode donné
    public function getEpisode(int $episode_id): ?Episode
    {
        $query =
            "SELECT episode.id as id_ep, episode.titre, episode.file, episode.duree, serie.titre as serieTitre, serie.id as serieID, episode.numero,episode.resume, episode.img 
             FROM episode inner Join serie on serie.id=episode.serie_id WHERE episode.id=?";
        $stmt=$this->pdo->prepare($query);
        $stmt->execute([$episode_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Episode(
                intval($data['id_ep']),
                intval($data['serieID']),
                $data['titre'],
                $data['file'],
                intval($data['duree']),
                $data['serieTitre'],
                intval($data['numero']),
                $data['resume'],
                $data['img']
            );
        }
        return null;
    }

    //ajoute u episode a une serie
    public function addEpisode(int $serie_id, Episode $ep) : ?bool
    {
        return null;
    }


    /**
     * @param int $user_id
     * @return array
     * retourne les preferences d'un user
     */
    public function getPref(int $user_id) : array
    {
        $query = "select id_serie from prefSerie2User where id_user = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array($user_id));
        $res = [];
        while ($row = $stmt->fetch()) {
            $serie = $this->getSerie($row["id_serie"]);
            $res[] = $serie;
        }
        return $res;
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

    public function removeSeriePref(int $serie_id, int $user_id) : bool {
        try {
            $query = 'delete from prefSerie2User where id_serie = ? and id_user = ?';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array($serie_id, $user_id));
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }


}