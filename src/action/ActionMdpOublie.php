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
            <h1>Réinitialiser votre mots de pass</h1>
            </br>
            <form action="?action=mdp-oublie" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
                
                <input type="submit" value="mdp-oubie">
            </form>
        HTML;

        return $tmp;
    }

    /**
     * @throws \Exception si erreur avec l'utilisation de l'objet PDO
     */
     public function lancerPost(): string
     {
         if (!isset($_POST['email'])) {
             return "<p>Email manquant impossible de réinitialiser le mot de passe.</p>";
         }

         $email = $_POST['email'];
         $repo = Repository::getInstance();

        $user_id=$repo->getUserByEmail($email);
         if ($user_id===null) {
             return "<p>Email non trouvé.</p>";
         }

         $token = bin2hex(random_bytes(16));
         $expire = date('Y-m-d H:i:s', time() + 3600);// anne moi jour heur format 24h minute ert seconde



         $repo->saveToken($user_id, $token, $expire);


         $resetLink = "http://".$_SERVER['HTTP_HOST']."/?action=reset-mdp&token=$token";

         $subject = "Réinitialisation de votre mot de passe";
         $message = "Bonjour,\n\nCliquez sur le lien suivant pour réinitialiser votre mot de passe :\n$resetLink\n\nCe lien est valide pendant 1 heure.";
         $headers = "From: no-reply@netvod.local";

         if (mail($email, $subject, $message, $headers)) {
             return "<p>Un email de réinitialisation a été envoyé à $email.</p>";
         } else {
             return "<p>Erreur lors de l'envoi de l'email.</p>";
         }
     }
}