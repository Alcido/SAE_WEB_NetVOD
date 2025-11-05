<?php

namespace NetVOD\src\action;

use NetVOD\src\auth\AuthnProvider;
use NetVOD\src\Exception\AuthnException;
use NetVOD\src\repository\Repository;

class ActionRegister extends Action
    {
    public function lancerGet(): string
    {
        $tmp =
        <<<HTML
            <h1>Register</h1>
            </br>
            <form action="?action=register" method="post">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" placeholder="Username" required autofocus>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
                 <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="submit" value="Register">
            </form>
        HTML;

        return $tmp;
    }

    /**
     * @throws \Exception si erreur avec l'utilisation de l'objet PDO
     */
    public function lancerPost(): string
    {
        $tmp = "<h1>Register</h1><p>";

        //on vérifie que les données renseignées soient conformes
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['username'],
                FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[\p{L}0-9 ._\-]+$/u']])){
            return $this->lancerGet() . "<script>alert('Invalid email or password')</script>";
        }

        $email = $_POST['email'] ;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $username = $_POST['username'];

        try {
            AuthnProvider::register($username, $email, $password);
        } catch (AuthnException $e) {
            return $this->lancerGet() . "<script>alert('Erreur : identifiant déjà présent !');</script>";
        }

        $tmp.= "L'enregistrement est effectué</p>";

        if (!isset($_SESSION['user'])) {
            header("Location: ?action=login");
        }

        return $tmp;
    }
}