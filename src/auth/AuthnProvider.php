<?php
declare(strict_types=1);

namespace NetVOD\src\auth;


use NetVOD\src\Exception\AuthnException;
use NetVOD\src\repository\Repository;

/**
 * Classe d'authentification
 */
class AuthnProvider
{

    /** Méthode de connexion
     * @param string $mail mail de connexion
     * @param string $mdp mot de passe de connexion
     * @return void
     * @throws AuthnException erreur de connexion
     * @throws \Exception exception potentielle avec l'utilisation de PDO
     */
    public static function signin(string $mail, string $mdp) : void {
        // On récupère l'utilisateur depuis la BDD
        $user = Repository::getInstance()->getUser($mail);
        // Si l'utilisateur n'existe pas
        if (!$user) {
            throw new AuthnException("Utilisateur n'existe pas");
        }
        // Si le mot de passe est invalide
        if (!password_verify($mdp, $user['pwd'])) {
            throw new AuthnException("Mot de passe incorrect");
        }
        // On ajoute l'utilisateur trouvé en session
        $value = $user['user'];
        $_SESSION['user'] = serialize($value);
    }

    /** Méthode d'inscription d'un utilisateur
     * @param string $mail mail d'inscription
     * @param string $mdp mot de passe d'inscription
     * @return void
     * @throws AuthnException erreur d'inscription
     */
    public static function register(string $pseudo, string $mail, string $mdp) : void {
        // On ajoute l'utilisateur à la BDD
        $user = Repository::getInstance()->createUser($pseudo, $mail, $mdp);
        // Si l'inscription s'est mal passée
        if ($user === false) {
            throw new AuthnException("Identifiant existe déja");
        }
    }

}