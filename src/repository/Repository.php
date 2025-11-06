<?php

namespace NetVOD\src\repository;

use Exception;
use NetVOD\src\auth\User;
use NetVOD\src\video\Episode;
use NetVOD\src\video\Serie;
use PDO;
use PDOException;
use Random\RandomException;

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
     * @param String $file
     * @return void
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
        try {
            $stmt = $this->pdo->prepare("INSERT INTO utilisateur (pseudo, email, password, role,verifie) VALUES (?, ?, ?, ?,0)");
            return $stmt->execute([$pseudo, $email, $password, $role]);
        }catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                //le user a deja un compte
                return false;
            }
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function validateUser(int $id) : bool {
        echo $id;
        try{
            $stmt = $this->pdo->prepare("UPDATE utilisateur SET verifie = 1 WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param int $user_id
     * @return User|null
     */
    public function getUser(string $mail) : ?User{
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$mail]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                intval($data['id']),
                intval($data['role']),
                strval($data['pseudo']),
                intval($data['verifie']),
                strval($data['password'])
            );
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

    /**
     * @param int $serie_id
     * @param int $user_id
     * @return bool|null
     */
    public function addSeriePref(int $serie_id, int $user_id):?bool{
        try {
            $query = "insert into prefSerie2User values (?, ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$user_id, $serie_id]);
        } catch (PDOException $e) {
            //la serie est deja dans les serie pref
            return false;
        }
        return true;
    }

    //ajoute serie en cours

    /**
     * @param int $serie_id
     * @param int $user_id
     * @param int $ep_num
     * @return bool|null
     */
    public function addSerieEnCours(int $serie_id, int $user_id, int $ep_num) : ?bool
    {
        try {
            if ($this->isEnCours($serie_id, $user_id)) {
                $query = "update enCours2User set num_ep = ? where id_serie = ? and id_user = ?";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$ep_num, $serie_id, $user_id]);
            } else {
                $query = "insert into enCours2User values (?,?,?)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$user_id, $serie_id, $ep_num]);
            }
            $this->verifDejaVu($serie_id, $ep_num, $user_id);
            return true;
        } catch (PDOException $e) {
            //la serie est deja en cours
            return false;
        }

    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public function getSeriesEncours(int $user_id) : ?array  {
        try {
            $query = "select id_serie from enCours2User where id_user = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1, $user_id);
            $stmt->execute();
            $res = [];
            while ($row = $stmt->fetch()) {
                $serie = $this->getSerie($row["id_serie"]);
                $res[] = $serie;
            }
            return $res;
        } catch (PDOException $e) {
            return null;
        }
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
            $stmt = $this->pdo->prepare("INSERT INTO notation (id_user, id_serie, date_comm, commentaire, note) VALUES (?,?,SYSDATE(),?,?)");
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
        try {
            $stmt = $this->pdo->prepare("SELECT Avg(note) FROM notation WHERE id_serie = ? group by id_serie");
            $stmt->execute([$serie_id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return floatval($data['Avg(note)']);
            }
            return null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }

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

    /**
     * @param int $episode_id
     * @return Episode|null
     */
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

    /**
     * @param int $serie_id
     * @param Episode $ep
     * @return bool|null
     */
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

    /**
     * @param int $serie_id
     * @param int $user_id
     * @return bool
     */
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

    /**
     * @param string $titre
     * @return array|null
     */
    public function getSerieRecherche(string $titre) : ?array{
        $query = "select id from serie where titre like ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array("%$titre%"));
        $res = [];
        while ($row = $stmt->fetch()) {
            $serie = $this->getSerie($row["id"]);
            $res[] = $serie;
        }
        if (sizeof($res) == 0) {
            return null;
        }
        return $res;
    }

    /**
     * @param int $user_id
     * @param string $field
     * @return bool
     */
    public function dellInfos(int $user_id, string $field):bool{
        $allowedFields = ['nom', 'prenom', 'genre', 'birth_date', 'adresse'];
        if (!in_array($field, $allowedFields)) return false;


        $sql = "UPDATE profil SET $field = NULL WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$user_id]);

    }

    /**
     * @param int $user_id
     * @param string $field
     * @param string $value
     * @return bool
     */
    public function  addInfos(int $user_id, string $field, string $value):bool
    {
        $allowedFields = ['nom', 'prenom', 'genre', 'birth_date', 'adresse'];
        if (!in_array($field, $allowedFields)) return false;

        if($value === '' ){
            $value = null;
        }

        $stmt = $this->pdo->prepare("INSERT INTO profil (id, $field)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE
            $field = VALUES($field)
        ");
        return $stmt->execute([$user_id,$value]);
    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public function getInfosUser(int $user_id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM profil WHERE id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $data;
        }
        return null;
    }

    /**
     * @param int $serie_id
     * @param int $user_id
     * @return array|null
     */
    public function isEnCours(int $serie_id, int $user_id) : ?array {
        $query = "select num_ep, id from enCours2User inner join episode on episode.serie_id = enCours2User.id_serie where id_serie = ? and id_user = ? and num_ep = numero";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array($serie_id, $user_id));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res === false ? null : $res;
    }

    /**
     * @param int $serie_id
     * @param int $num_ep
     * @param int $user_id
     * @return bool|null
     */
    public function verifDejaVu (int $serie_id, int $num_ep, int $user_id) : ?bool {
        try {
            $query = "select max(numero) as lastEp from serie inner join episode on serie.id = episode.serie_id where serie.id = ? group by serie_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$serie_id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res["lastEp"] === $num_ep) {
                $query = "delete from enCours2User where id_serie = ? and id_user = ?";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$serie_id, $user_id]);
                $query = "insert into serieFinie2User values (?, ?)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(array($user_id, $serie_id));
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public function getSeriesDejaVu(int $user_id) : ?array  {
        try {
            $query = "select id_serie from serieFinie2User where id_user = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1, $user_id);
            $stmt->execute();
            $res = [];
            while ($row = $stmt->fetch()) {
                $serie = $this->getSerie($row["id_serie"]);
                $res[] = $serie;
            }
            return $res;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * @param string $email
     * @return int|null
     */
    public function getUserByEmail(string $email): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data && isset($data['id'])) {
            return (int) $data['id'];
        }
        return null;
    }


    /**
     * @param int $user_id
     * @param bool $expire
     * @return string|null
     * @throws RandomException
     */
    public function saveToken(int $user_id, bool $expire) : ?string
    {
        $token = bin2hex(random_bytes(16));

        if ($expire){
            $expire = date('Y-m-d H:i:s', strtotime('+1 day'));
        }else{
            $expire = date('Y-m-d H:i:s', strtotime('+360 day'));
        }

        try {
            $stmt = $this->pdo->prepare("insert into token (id, token, expire) values (?, ?, ?)");
            $stmt->execute([$user_id, $token, $expire]);
            return $token;
        }

        catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * @param int $user_id
     * @return array|null
     */
    public function getToken(int $user_id) : ?array
    {
        try {
            $this->updateToken();
            $stmt = $this->pdo->prepare("SELECT token FROM token where id=?");
            $stmt->execute([$user_id]);
            $arr = [];
            while ($row = $stmt->fetch()){
                $arr[] = $row['token'];
            }

            return $arr;
        }
        catch (PDOException $e) {
            return null;
        }
    }

    /**
     * @return void
     */
    public function updateToken(){
        $query = "delete from token where expire < current_time()";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    /**Méthode permettant de récupérer les genres de la base de donnée
     * @return array|null la liste des genres
     */
    public function getGenre() : ?array {
        $query = "select genre from genre";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**Méthode permettant de récupérer les types de public de la base de donnée
     * @return array|null la liste des types de public
     */
    public function getTypePublic() : ?array {
        $query = "select type_public from type_public";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param int $user_id
     * @param string $password
     * @return bool
     */
    public function changeMdp(int $user_id, string $password) : bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilisateur SET password = ? WHERE id = ?");
        return $stmt->execute([$password, $user_id]);
    }

    /**
     * @param int $user_id
     * @param string $token
     * @return bool
     */
    public function dellToken(int $user_id, string $token) : bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM token WHERE id = ? and token = ?");
        return $stmt->execute([$user_id, $token]);
    }

    /**
     * @param int $episode_id
     * @return Episode|null
     * @throws Exception
     */
    public function getNextEpisode(int $episode_id): ?Episode
    {
        $stmt = $this->pdo->prepare("SELECT serie_id, numero FROM episode WHERE id = ?");
        $stmt->execute([$episode_id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$current) {
            throw new \Exception("Épisode introuvable.");
        }

        $serieId = $current['serie_id'];
        $nextNumero = $current['numero'] + 1;

        // Chercher le prochain épisode dans la même série
        $query = "
        SELECT episode.id as id_ep, episode.titre, episode.file, duree, serie.titre as serieTitre, serie.id as serieID, numero, resume, episode.img
        FROM episode
        INNER JOIN serie  ON serie.id = episode.serie_id
        WHERE episode.serie_id = ? AND numero = ?
        LIMIT 1";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$serieId, $nextNumero]);
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
}