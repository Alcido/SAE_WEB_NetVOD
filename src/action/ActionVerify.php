<?php

namespace NetVOD\src\action;

use NetVOD\src\action\Action;
use NetVOD\src\auth\AuthnProvider;
use NetVOD\src\repository\Repository;

class ActionVerify extends Action
{

    /**
     * @inheritDoc
     */
    public function lancerGet(): string
    {
        if (isset($_GET['token']) && isset($_SESSION['token']) && isset($_SESSION['unverifiedUser'])){
            if ($_GET['token'] === $_SESSION['token']){
                AuthnProvider::validateUser($_SESSION['unverifiedUser']);
                unset($_SESSION['token']);
                unset($_SESSION['unverifiedUser']);
                return "<h1>Vous êtes maintenant validé</h1>";
            }

            return "<h1>Token invalide</h1>";
        }
        return "<h1>Token manquant</h1>";
    }

    /**
     * @inheritDoc
     */
    public function lancerPost(): string
    {
        return "Why are you here?";
    }
}