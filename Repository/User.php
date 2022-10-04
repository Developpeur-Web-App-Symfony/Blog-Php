<?php

namespace Repository;

use Exception;
use Framework\Model;
use \PDO;

class User extends \Framework\Model
{
    private object $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function checkEmailInBdd(): int
    {
        $sql = 'SELECT email FROM users WHERE email=:email';
        $req = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail()));
        return $req->rowCount();
    }

    public function checkUsernameInBdd(): int
    {
        $sql = 'SELECT username FROM users WHERE username=:username AND id!=:id';
        $req = $this->executeRequest($sql, array(
            'username' => $this->user->getUsername(),
            'id' => $this->user->getId()));

        return $req->rowCount();
    }

    public function checkOtherMailInBdd(): int
    {
        $sql = 'SELECT email FROM users WHERE email=:email AND id!=:id';
        $req = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
            'id' => $this->user->getId(),
        ));
        return $req->rowCount();
    }

    /**
     * @throws Exception
     */
    public function getEmailAndTokenUserInBdd()
    {
        $email = $this->user->getEmail();
        $sql = 'SELECT * FROM users WHERE email= :email';
        $user = $this->executeRequest($sql, array(
            'email' => $email,
        ));
        if ($user->rowCount() === 1) {
            $user->setFetchMode(PDO::FETCH_CLASS, \Model\User::class);
            return $user->fetch();
        } else {
            throw new Exception("Aucun utilisateur ne correspond à l'adresse email '$email'");
        }
    }

    public function updateToken()
    {
        $sql = 'UPDATE users SET token=:token WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
            'token' => $this->user->getToken()
        ));
    }

    public function updateUser()
    {
        $sql = 'UPDATE users SET role_level=:role_level, is_valid=:is_valid, email=:email WHERE id=:id';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail(),
            'role_level' => $this->user->getRoleLevel(),
            'is_valid' => $this->user->getValid(),
        ));
    }

    public function updateAccountUser(): bool
    {
        $sql = 'UPDATE users SET username=:username, role_level=:role_level, email=:email WHERE id=:id';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail(),
            'username' => $this->user->getUsername(),
            'role_level' => $this->user->getRoleLevel(),
        ));
        return true;
    }

    public function updatePassword()
    {
        $sql = 'UPDATE users SET password=:password, token=:token WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword(),
            'token' => $this->user->getToken()
        ));
    }

    public function updateNewPassword()
    {
        $sql = 'UPDATE users SET password=:password WHERE email=:email';
        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword()
        ));
    }

    public function getUser($userId)
    {
        $sql = 'SELECT id as id, created_at as createdAt, role_level as role_level, is_valid as is_valid, username as username, email as email, password as password, token as token, ip as ip FROM users WHERE id=:id';

        $user = $this->executeRequest($sql, array(
            'id' => $userId,
        ));

        if ($user->rowCount() == 1) {
            $user->setFetchMode(PDO::FETCH_CLASS, \Model\User::class);
            return $user->fetch();
        } else {
            throw new \Exception("Aucun utilisateur ne correspond à l'identifiant '$userId'");
        }
    }

    public function getUsers($role = null, $isValid = null)
    {
        $sql = 'SELECT u.id, u.username, u.role_level FROM users as u 
    INNER JOIN roles as r on u.role_level = r.level';
        if ($role !== null && $isValid !== null){
            $sql .=' WHERE r.level=:role_level AND u.is_valid=:isValid';
            $req = $this->executeRequest($sql, array(
                'role_level' => $role,
                'isValid' => $isValid
            ));
            return $req->fetchAll(PDO::FETCH_CLASS, \Model\User::class);
        } elseif ($role !== null) {
            $sql .= ' WHERE r.level=:role_level';
        } elseif ($isValid !== null) {
            $sql .= ' WHERE u.is_valid=:isValid';
        }
        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\User::class);
    }

    public function getAllUsers(): array
    {
        $sql = 'SELECT u.id, u.username, u.email,u.created_at,u.is_valid, u.role_level, r.slug FROM users as u 
    INNER JOIN roles as r on u.role_level = r.level';

        $req = $this->executeRequest($sql);
        return $req->fetchAll(PDO::FETCH_CLASS, \Model\User::class);
    }

    public function getUserInBdd($valid = null)
    {
        $sql = 'SELECT id, token, username, email, password, created_at, role_level, is_valid FROM users WHERE email= :email';
        if ($valid !== null) {
            $sql .= ' AND is_valid = :is_valid';
            $req = $this->executeRequest($sql, array(
                'email' => $this->user->getEmail(),
                'is_valid' => $valid,
            ));
            $req->setFetchMode(PDO::FETCH_CLASS, \Model\User::class);
            return $req->fetch();
        }
        $req = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
        ));
        $req->setFetchMode(PDO::FETCH_CLASS, \Model\User::class);
        return $req->fetch();
    }

    public function save(): bool
    {
        $sql = "INSERT INTO users(username, email, password, role_level, is_valid, created_at, token, ip) VALUES(:username, :email, :password, :role_level, :is_valid, :createdAt, :token, :ip)";

        $req = $this->executeRequest($sql, array(
            'username' => $this->user->getUsername(),
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword(),
            'role_level' => $this->user->getRoleLevel(),
            'is_valid' => $this->user->getValid(),
            'createdAt' => $this->user->getCreatedAt(),
            'token' => $this->user->getToken(),
            'ip' => $this->user->getIp()
        ));
        return true;
    }

    public function updateIpUser()
    {
        $sql = 'UPDATE users SET ip=:ip WHERE email=:email';

        $updateUser = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
            'ip' => $this->user->getIp()
        ));
    }

    public function getIpUser() {
        $sql = 'SELECT ip FROM users WHERE id= :id';

        $req = $this->executeRequest($sql, array(
            'id' => $this->user->getId(),
        ));
        $req->setFetchMode(PDO::FETCH_CLASS, \Model\User::class);
        return $req->fetch();
    }
}