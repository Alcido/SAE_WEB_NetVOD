<?php
declare(strict_types=1);
namespace NetVOD\src\auth;

class User
{
    private bool $verified = false;

    private int $id;
    private int $role;
    private string $pseudo;

    /**
     * @param int $id id de l'utilisateur
     * @param int $role role de l'utilisateur
     * @param string $pseudo pseudo de l'utilisateur
     */
    public function __construct(int $id, int $role, string $pseudo,bool $verified) {
        $this->verified=$verified;
        $this->id = $id;
        $this->role = $role;
        $this->pseudo = $pseudo;
    }

    /**
     * @param string $name nom de l'attribut recherché
     * @return mixed valeur de l'attribut recheché
     * @throws \Exception si l'attribut n'existe pas, retourne une exception
     */
    public function __get(string $name) : mixed{
        if (property_exists($this, $name)){
            return $this->$name;
        } else {
            throw new \Exception("Property $name does not exist");
        }
    }

}