<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionAddPref extends Action
{
    public function lancerPost(): string
    {
        $serie_id = $_GET['serie_id'];
        $user_id = unserialize($_SESSION['user'])->id;

            $repo = Repository::getInstance();
            if ($_POST['addFavorite'] == "add"){
                $repo->addSeriePref($serie_id, $user_id);
            }else{
                $repo->removeSeriePref($serie_id, $user_id);
            }
            header("Location: ?action=catalogue");
            return "";
    }

    public function lancerGet(): string
    {
        return $this->lancerPost();
    }
}