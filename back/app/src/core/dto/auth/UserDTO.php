<?php
namespace slv\core\dto\auth;

use slv\core\dto\DTO;
use Respect\Validation\Validator;

class UserDTO extends DTO {
    protected string $id;
    protected string $email;
    protected string $hashed_password;
    protected string $accessToken;
    protected string $refreshToken;

    public function __construct(string $id, string $email, string $hashed_password) {
        $this->id = $id;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->accessToken = "";
        $this->refreshToken = "";

        $validator = Validator::attribute('email', Validator::email()->notEmpty())
                              ->attribute('id', Validator::stringType()->notEmpty())
                              ->attribute('hashed_password', Validator::stringType()->notEmpty());

        $this->setBusinessValidator($validator);
    }

    public function setAccessToken(string $accessToken): void {
        $this->accessToken = $accessToken;
    }

    public function setRefreshToken(string $refreshToken): void {
        $this->refreshToken = $refreshToken;
    }
}
