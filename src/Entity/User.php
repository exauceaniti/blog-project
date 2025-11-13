<?php
namespace Src\Entity;

class User {
    public ?int $id = null;
    public string $nom;
    public string $email;
    public string $password;
    public string $role = 'user';
    public ?string $date_inscription = null;
}
