<?php
declare(strict_types=1);

namespace NetVOD\src\action;

/**
 * Classe abstraite parente de toutes les autres actions
 */
abstract class Action {

    // Attributs
    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    /**
     * Constructeur
     */
    public function __construct(){
        // On récupère les valeurs des attributs via la variable SERVER
        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Méthode d'exécution sur l'instanciation
     * @return string résultat de l'exécution en HTML
     */
    public function __invoke() : string {
        return $this->execute();
    }

    /**
     * Méthode d'exécution
     * @return string résultat de l'exécution en HTML
     */
    public function execute() : string {

        // Dans le cas d'un GET
        if ($this->http_method == "GET") {
            return $this->lancerGet();
        }

        // Dans le cas d'un POST
        if ($this->http_method == "POST") {
            return $this->lancerPost();
        }

        // Si la méthode est différente
        return "Erreur de méthode";
    }

    /**
     * Méthode abstraite de lancement du GET
     * @return string
     */
    abstract public function lancerGet() : string;

    /**
     * Méthode abstraite de lancement du POST
     * @return string
     */
    abstract public function lancerPost() : string;

}