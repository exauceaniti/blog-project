<?php
namespace Src\Entity;

class User {
    public ?int $id = null;
    public string $nom;
    public string $email;
    public string $password;
    public string $role = 'user';
    public ?string $date_inscription = null;


    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->date_inscription = $data['date_inscription'] ?? null;



}

}