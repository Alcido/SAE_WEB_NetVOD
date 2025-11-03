<?php

namespace NetVOD\src\action;

use NetVOD\src\action\Action;
use NetVOD\src\repository\Repository;

class ActionLogIn extends Action
{

    public function lancerGet(): string
    {
        if (isset($_SESSION['user'])) {
            $tmp = "<h1>LogIn</h1><h2>You are already logged in</h2>";
            $tmp .= "<form action=\"?action=logIn\" method=\"post\">";
            $tmp .= "<input type=\"submit\" name=\"logOut\" value=\"LogOut\">";
        }else {
            $tmp = <<<HTML
            </br> 
            <form action="?action=logIn" method="post">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Register">
            </form>
            HTML;
        }
        return $tmp;
    }
    public function lancerPost(): string
    {
        $tmp = "<h1>LogIn</h1><h2>";

        if (isset($_POST['email']) && isset($_POST['password'])) {

            $repo = Repository::getInstance();
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            if($repo->isUser($email, $password)){
                $_SESSION['user'] = $email;
                $tmp.= "User added";
            }else{
                $tmp.= "Some Problem occured";
            }

        }else if (isset($_POST['logOut'])) {
            unset($_SESSION['user']);
            $tmp.= "User Logged Out";
        }else{
            $tmp.= "Data not set";
        }

        $tmp.= "</h2>";

        return $tmp;
    }
}