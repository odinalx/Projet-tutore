<?php
namespace slv\core\domain\entities\user;

use slv\core\domain\entities\Entity;
use Ramsey\Uuid\Guid\Guid;

class User extends Entity{
    const ROLE_ADMIN = 15;
    const ROLE_RESPONSABLE = 10;
    const ROLE_ENCADRANT = 5;
    const ROLE_ADHERENT = 0;

    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $password;
    protected int $role;
    protected \DateTimeImmutable $created_at;
    protected \DateTimeImmutable $updated_at;


    public function __construct(string $nom, string $prenom, string $email, string $password){
        $this->setID(Guid::uuid4()->toString());
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = self::ROLE_ADHERENT; 
        $this->created_at = new \DateTimeImmutable(); 
        $this->updated_at = new \DateTimeImmutable();      
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): void{
        $this->updated_at = $updated_at;
    }

}