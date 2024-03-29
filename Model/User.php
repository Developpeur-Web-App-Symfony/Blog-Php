<?php
namespace Model;

use Exception;
use Framework\Controller;
use Services\Validator;
use Framework\Model;
use \PDO;

class User extends \Framework\Model
{
    const LENGTH_TOKEN = 78;

    private mixed $id;
    private mixed $username;
    private mixed $email;
    private mixed $password;
    private mixed $cPassword;
    private mixed $created_at;
    private mixed $role_level;

    private mixed $is_valid;
    private mixed $token;
    private mixed $ip;

    private array $errorsMsg = [];

    public function getId(): mixed
    {
        return $this->id;
    }


    public function setId($id): void
    {
        $this->id = $id;
    }


    public function getUsername(): mixed
    {
        return $this->username;
    }


    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getEmail(): mixed
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword(): mixed
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getCPassword(): mixed
    {
        return $this->cPassword;
    }

    public function setCPassword($cPassword): void
    {
        $this->cPassword = $cPassword;
    }

    public function getCreatedAt(): mixed
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getRoleLevel(): mixed
    {
        return $this->role_level;
    }

    /**
     * @param mixed $role_level
     */
    public function setRoleLevel(mixed $role_level): void
    {
        $this->role_level = $role_level;
    }

    public function getValid(): mixed
    {
        return $this->is_valid;
    }

    public function setValid($is_valid): void
    {
        $this->is_valid = $is_valid;
    }

    public function getToken(): mixed
    {
        return $this->token;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getIp(): mixed
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp(mixed $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    public function hydrate($user)
    {
        $this->setCPassword($user->password);
        $this->setEmail($user->email);
        $this->setUsername($user->username);
        $this->setRoleLevel($user->role_level);
        $this->setValid($user->is_valid);
        $this->setToken($user->token);
        $this->setCreatedAt($user->created_at);
        $this->setId($user->id);
    }

    public function passwordHash()
    {
        $this->setPassword(password_hash($this->getPassword(), PASSWORD_BCRYPT));
        $this->setCPassword(null);
    }

    /**
     * @throws Exception
     */
    public function setDataNewUser(){
        $dateNow = new \DateTime();
        $this->setCreatedAt($dateNow->format('Y-m-d H:i:s'));
        $this->setRoleLevel(Controller::VISITOR);
        $this->setValid(Controller::IS_VALID ['NO_VALID']);
        $this->setIp($_SERVER['REMOTE_ADDR']);
        $this->generateToken();
    }

    /**
     * @throws Exception
     */
    public function generateToken()
    {
        $this->setToken(bin2hex(random_bytes(self::LENGTH_TOKEN)));
    }
}