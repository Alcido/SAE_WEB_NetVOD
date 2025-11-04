<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionAddPref extends Action
{
    public function lancerGet(): string
    {
        $serie_id = $_GET['serie_id'];
        $ajout = Repository::getInstance()->addSeriePref($serie_id);
        if (!$ajout) {
            return "<p>Cette série ne peut pas être ajoutée à votre liste de préférences.</p>";
        } else return "<p>Série ajoutée à votre liste de préférences.</p>";
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}