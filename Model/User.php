<?php
namespace Model;

use Exception;
use Framework\Controller;
use Services\Validator;
use Framework\Model;
use \PDO;

class User extends \Framework\Model
{
    const MAX_LENGTH_USERNAME = 16;
    const LENGTH_TOKEN = 78;

    private mixed $id;
    private mixed $username;
    private mixed $email;
    private mixed $password;
    private mixed $cPassword;
    private mixed $created_at;
    private mixed $role_id;

    private mixed $valid;
    private mixed $token;

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
        return $this->valid;
    }

    public function setValid($valid): void
    {
        $this->valid = $valid;
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
        $this->setValid($user->valid);
        $this->setToken($user->token);
        $this->setCreatedAt($user->created_at);
        $this->setId($user->id);
    }

    public function registerValidate(): bool
    {
        if ($this->checkEmailInBdd() === 0) {
            return true;
        }
        return false;
    }

    public function passwordHash()
    {
        $this->setPassword(password_hash($this->getPassword(), PASSWORD_BCRYPT));
        $this->setCPassword(null);
    }

    public function checkEmailInBdd(): int
    {
        $sql = 'SELECT email FROM users WHERE email=:email';
        $req = $this->executeRequest($sql, array(
            'email' => $this->getEmail()));

        return $req->rowCount();
    }

    /**
     * @throws Exception
     */
    public function getEmailAndTokenUserInBdd($userEmail)
    {
        $sql = 'SELECT * FROM users WHERE email= :email';
        $user = $this->executeRequest($sql, array(
            'email' => $this->getEmail(),
        ));

        if ($user->rowCount() === 1){
            $userdata= $user->setFetchMode(PDO::FETCH_OBJ);
            return $user->fetch();
        } else {
            throw new Exception("Aucun utilisateur ne correspond à l'adresse email '$userEmail'");
        }
    }

    public function updateUser()
    {
        $sql = 'UPDATE users SET role_id=:roleId, is_valid=:valid, email=:email WHERE id=:id';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'roleId' => $this->getRoleId(),
            'valid' => $this->getValid(),
        ));
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

    public function getUser($userId)
    {
        $sql = 'SELECT id as id, created_at as createdAt, role_id as role, is_valid as is_valid, username as username, email as email, password as password, token as token FROM users WHERE id=:id';

        $user = $this->executeRequest($sql, array(
            'id' => $userId,
        ));

        if ($user->rowCount() == 1) {
            $user->setFetchMode(PDO::FETCH_OBJ);
            return $user->fetch();
        }
        else {
            throw new \Exception("Aucun utilisateur ne correspond à l'identifiant '$userId'");
        }
    }

    public function save(): bool
    {
        $this->passwordHash();
        $sql = "INSERT INTO users(username, email, password, role_id, is_valid, created_at, token) VALUES(:username, :email, :password, :role_id, :is_valid, :createdAt, :token)";

        $req = $this->executeRequest($sql, array(
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'role_id' => $this->getRoleId(),
            'is_valid' => $this->getValid(),
            'createdAt' => $this->getCreatedAt(),
            'token' => $this->getToken(),
        ));
        return true;
    }
}