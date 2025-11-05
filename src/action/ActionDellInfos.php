<?php
namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionDellInfos extends Action
{
    public function lancerGet(): string {
        return $this->lancerPost(); // on peut gérer GET ou POST
    }

    public function lancerPost(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<p>Vous devez être connecté pour supprimer des infos.</p>";
        }

        if (!isset($_GET['value'])) {
            return "<p>Champ non précisé.</p>";
        }

        $field = $_GET['value'];
        $allowedFields = ['nom', 'prenom', 'genre', 'birth_date', 'adresse'];

        if (!in_array($field, $allowedFields)) {
            return "<p>Champ invalide.</p>";
        }

        $user = unserialize($_SESSION['user']);
        $user_id = $user->id;

        $repo = Repository::getInstance();
        $repo->dellInfos($user_id, $field);

        header("Location: ?action=infos");
        exit;
    }
}
