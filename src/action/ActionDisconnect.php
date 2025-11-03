<?php

namespace NetVOD\src\action;

class ActionDisconnect extends Action{

    /**
     * @return string renvoi le lancerPost car on n'accede jamais à cette action par un get logiquement
     */
    public function lancerGet(): string
    {
        return $this->lancerPost();
    }

    /**
     * @return string ramene directement à la page de login
     */
    public function lancerPost(): string
    {
        //on supprime simplement l'utilisateur de la session
        unset($_SESSION['user']);
        header('Location: ?action=login');
        return "";
    }

}