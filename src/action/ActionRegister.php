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
                <label for="password"> Mot de passe <span class="tooltip">*</span></label>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Répéter le mot de passe</label>
                <input type="password" name="password2" id="password" placeholder="Password" required>
                <input type="submit" value="Register">
            </form>
            <a href="?action=login" "><button class="btn">Se connecter</button></a>
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


        if (!$this->checkPasswordStrength($_POST['password'], 8)) {
            return $this->lancerGet() . "<script>
                alert('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
            </script>";
        }

        if ($_POST['password'] !== $_POST['password2']) {
            return "<h1>Les mots de passe ne correspondent pas.</h1>";
        }

        $email = $_POST['email'] ;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $username = $_POST['username'];

        try {
            AuthnProvider::register($username, $email, $password);
        } catch (AuthnException $e) {
            return $this->lancerGet() . "<script>alert('Erreur : identifiant déjà présent !');</script>";
        }


        //Gestion du Token
        $currentURL = "https://$_SERVER[HTTP_HOST]" . explode('?',$_SERVER['REQUEST_URI'])[0];
        $user = Repository::getInstance()->getUser($email)->id;

        $token =Repository::getInstance()->saveToken($user,true);

        $tmp.= "L'enregistrement est presque effectué</p>
                <p> Veuillez cliquer sur ce lien :</p>
                <a href='$currentURL?action=verify&token=$token&id=$user'>$currentURL?action=verify&token=$token&id=$user</a>";




        return $tmp;
    }



    public function checkPasswordStrength(string $pass, int $minimumLength): bool
    {
        $length = strlen($pass) >= $minimumLength;  // ✅ longueur minimale correcte
        $digit = preg_match("#\d#", $pass);         // au moins un chiffre
        $special = preg_match("#\W#", $pass);       // au moins un caractère spécial
        $lower = preg_match("#[a-z]#", $pass);      // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass);      // au moins une majuscule

        return $length && $digit && $special && $lower && $upper;
    }
}