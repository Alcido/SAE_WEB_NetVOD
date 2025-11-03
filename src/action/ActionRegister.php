<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionRegister extends Action
    {
    public function lancerGet(): string
    {
        $tmp = "<h1>Register</h1>";

        $tmp.= "</br> <form action=\"?action=register\" method=\"post\">
                <input type=\"text\" name=\"username\" placeholder=\"Username\">
                <input type=\"email\" name=\"email\" placeholder=\"Email\">
                <input type=\"password\" name=\"password\" placeholder=\"\">
                <input type=\"submit\" value=\"Register\">
            ";


        $tmp.= "</form>";

        return $tmp;
    }
    public function lancerPost(): string
    {
        $tmp = "<h1>Register</h1><h2>";

        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

            $repo = Repository::getInstance();
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            if($repo->addUser($username, $email, $password)){
                $tmp.= "User added";
            }else{
                $tmp.= "Some Problem occured";
            }

        }else{
            $tmp.= "Data not set";
        }

        $tmp.= "</h2>";

        return $tmp;
    }
}