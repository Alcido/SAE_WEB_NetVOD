<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionMdpOublie extends Action
{
    public function lancerGet(): string
    {
        return <<<HTML
            <h1>Réinitialiser votre mot de passe</h1>
            <form action="?action=mdp-oublie" method="post">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" placeholder="Votre adresse email" required>
                <input type="submit" value="Envoyer le lien de réinitialisation">
            </form>
        HTML;
    }

    public function lancerPost(): string
    {
        if (!isset($_POST['email'])) {
            return "<p>Adresse email manquante.</p>";
        }

        $email = trim($_POST['email']);
        $repo = Repository::getInstance();
        $user_id = $repo->getUserByEmail($email);

        if ($user_id === null) {
            return "<p>Aucun compte associé à cette adresse email.</p>";
        }


        $token = $repo->saveToken($user_id, true);

        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/?action=reset-mdp&id=$user_id&token=$token";

        return <<<HTML
            <h1>Lien de réinitialisation envoyé</h1>
            <p>Un lien de réinitialisation a été envoyé à votre adresse email.</p>
            <p><a href=$resetLink>Lien direct</a></p>
        HTML;
    }
}
