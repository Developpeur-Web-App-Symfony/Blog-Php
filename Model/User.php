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
    private mixed $role_id;

    private mixed $is_valid;
    private mixed $token;

    /** A supprimer **/
    private int $errors = 0;
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
    public function getRoleId(): mixed
    {
        return $this->role_id;
    }

    /**
     * @param mixed $role_id
     */
    public function setRoleId(mixed $role_id): void
    {
        $this->role_id = $role_id;
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
        $this->setRoleId($user->role_id);
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
        $this->setRoleId(Controller::VISITOR);
        $this->setValid(Controller::IS_VALID ['NO_VALID']);
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