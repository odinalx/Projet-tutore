<?php
namespace slv\core\dto\auth;

use slv\core\dto\DTO;
use Respect\Validation\Validator;

class CredentialsDTO extends DTO {
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password) {
        $this->email = $email;
        $this->password = $password;
        $validator = Validator::attribute('email', Validator::email()->notEmpty())
                              ->attribute('password', Validator::stringType()->notEmpty());

        $this->setBusinessValidator($validator);
    }
}