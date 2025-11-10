<?php

namespace NetVOD\src\action;

use InvalidArgumentException;
use NetVOD\src\action\Action;
use NetVOD\src\auth\AuthnProvider;
use NetVOD\src\repository\Repository;
use NetVOD\src\exception\AuthnException;

class ActionLogIn extends Action
{

    public function lancerGet(): string
    {
        if (isset($_SESSION['user'])) {
            $tmp = <<<HTML
            <h1>Connexion</h1><h2>Vous êtes déjà connecté.</h2>
            <form action="?action=disconnect" method="post">
                <input type="submit" name="logOut" value="Se déconnecter">
            </form>
            HTML;
        } else {
            $tmp = <<<HTML
            <h1>Connexion à NetVOD</h1>
            </br> 
            <form action="?action=logIn" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="utilisateur@mail.com" required autofocus>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="mot de passe" required>
                <input type="submit" value="Se connecter">
            </form>

            <a href="?action=register" "><button class="btn">Créer un compte</button></a>
            <a href="?action=mdp-oublie" ><button class="btn">Mot de passe oublié ?</button></a>
            HTML;
        }
        return $tmp;
    }

    /**
     * @throws \Exception
     */
    public function lancerPost(): string
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                AuthnProvider::signin($email, $password);
                // On renvoit la page d'accueil
                header('Location: ?action=default');
            } catch (AuthnException $e) {
                // Erreur de connexion
                $html = "<script>alert('Erreur : identifiants incorrects ! Merci de créer un compte ou de vérifier les informations de connexion.');</script>" . $this->lancerGet();
            }
        }
        return $html;
    }
}