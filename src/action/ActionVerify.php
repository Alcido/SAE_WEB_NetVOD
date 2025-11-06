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
            $isokay = false;
            $repo = Repository::getInstance();
            $storedToken = $repo->getToken($_GET['id']);
            foreach ($storedToken as $token){
                if ($token === $_GET['token']){
                    $isokay = true;
                }
            }
            if ($isokay){
                AuthnProvider::validateUser($_GET['id']);
                return "<h1>Vous êtes maintenant validé</h1>";
            }
            echo "$storedToken  compareTo  $_GET[token]";
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