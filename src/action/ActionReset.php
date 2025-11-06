<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionReset extends Action
{
    public function lancerGet(): string
    {
        if (!isset($_GET['id'], $_GET['token'])) {
            return "<h1>Token ou identifiant manquant</h1>";
        }

        $userId = $_GET['id'];
        $token = $_GET['token'];
        $repo = Repository::getInstance();

        $storedTokens = $repo->getToken($userId);

        $isValid = false;
        foreach ($storedTokens as $storedToken) {
            if ($storedToken === $token) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            return "<h1>Token invalide</h1>";
        }

        return <<<HTML
            <h1>Réinitialiser votre mot de passe</h1>
            <form action="?action=reset-mdp&id=$userId&token=$token" method="post">
                <label for="password">
                    Nouveau mot de passe 
                    <span class="tooltip">*</span>
                </label>
                <input type="password" name="password" id="password"  placeholder="Password" required>

                <label for="password2">Répéter le mot de passe</label>
                <input type="password" name="password2" id="password2"  placeholder="Password" required>

                <input type="submit" value="Réinitialiser">
            </form>
        HTML;
    }

    public function lancerPost(): string
    {
        if (!isset($_GET['id'], $_GET['token'], $_POST['password'], $_POST['password2'])) {
            return "<p>Données manquantes.</p>";
        }

        $userId = $_GET['id'];
        $token = $_GET['token'];
        $repo = Repository::getInstance();

        $storedTokens = $repo->getToken($userId);

        $isValid = false;
        foreach ($storedTokens as $storedToken) {
            if ($storedToken === $token) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            return "<h1>Token invalide</h1>";
        }

        if ($_POST['password'] !== $_POST['password2']) {
            return "<h1>Les mots de passe ne correspondent pas.</h1>";
        }

        $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $repo->changeMdp($userId, $hashed);
        $repo->dellToken($userId, $token);

        return "<h1>Mot de passe mis à jour avec succès !</h1>
                <p><a href='?action=login'>Se connecter</a></p>";
    }
}
