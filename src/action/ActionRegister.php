<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionRegister extends Action
    {
    public function lancerGet(): string
    {
        $tmp = "<h1>Register</h1>";

        $tmp.=
        <<<HTML
            </br> <form action="?action=register" method="post">
                <input type="text" name="username" placeholder="Username">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Register">
        HTML;


        $tmp.= "</form>";

        return $tmp;
    }

    /**
     * @throws \Exception
     */
    public function lancerPost(): string
    {
        $tmp = "<h1>Register</h1><h2>";

        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

            $repo = Repository::getInstance();
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['password'], FILTER_VALIDATE_REGEXP) || !filter_var($_POST['username'], FILTER_VALIDATE_REGEXP)){
                throw new \Exception("Invalid email or password");
            }

            $email = $_POST['email'] ;
            $password = $_POST['password'];
            $username = $_POST['username'];

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