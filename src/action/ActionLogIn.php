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
            <h1>LogIn</h1><h2>You are already logged in</h2>
            <form action="?action=disconnect" method="post">
                <input type="submit" name="logOut" value="LogOut">
            </form>
            HTML;
        } else {
            $tmp = <<<HTML
            </br> 
            <form action="?action=logIn" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="utilisateur@mail.com" required autofocus>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="submit" value="LogIn">
            </form>

            <a href="?action=register"><button>Register</button></a>
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

            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['password'], FILTER_VALIDATE_REGEXP)) {
                throw new InvalidArgumentException("Invalid email or password");
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                AuthnProvider::signin($email, $password);
                // On renvoit la page d'accueil
                header('Location: ?action=default');
            } catch (AuthnException $e) {
                // Erreur de connexion
                $html = "<script>alert('Erreur : identifiants incorrects ! Merci de créer un compte ou de vérifier les informations de connexion');</script>" . $this->lancerGet();
            }
        }
        return $html;
    }
}