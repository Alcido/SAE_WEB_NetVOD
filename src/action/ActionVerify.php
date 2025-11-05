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
        if (isset($_GET['token']) && isset($_GET['id'])){
            $repo = Repository::getInstance();
            $storedToken = $repo->getToken($_GET['id']);
            if ($_GET['token'] === $storedToken){
                AuthnProvider::validateUser($_GET['id']);
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