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
            $userdata = $user->setFetchMode(PDO::FETCH_OBJ);
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
        $sql = 'UPDATE users SET role_id=:role_id, is_valid=:is_valid, email=:email WHERE id=:id';
        $updateUser = $this->executeRequest($sql, array(
            'id' => $this->user->getId(),
            'email' => $this->user->getEmail(),
            'role_id' => $this->user->getRoleId(),
            'is_valid' => $this->user->getValid(),
        ));
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

    public function getUser($userId)
    {
        $sql = 'SELECT id as id, created_at as createdAt, role_id as role_id, is_valid as is_valid, username as username, email as email, password as password, token as token FROM users WHERE id=:id';

        $user = $this->executeRequest($sql, array(
            'id' => $userId,
        ));

        if ($user->rowCount() == 1) {
            $user->setFetchMode(PDO::FETCH_OBJ);
            return $user->fetch();
        } else {
            throw new \Exception("Aucun utilisateur ne correspond à l'identifiant '$userId'");
        }
    }

    public function getUserInBdd($valid = null)
    {
        $sql = 'SELECT id, token, username, email, password, created_at, role_id, is_valid FROM users WHERE email= :email';
        if ($valid !== null) {

            $sql .= ' AND is_valid = :is_valid';
            $req = $this->executeRequest($sql, array(
                'email' => $this->user->getEmail(),
                'is_valid' => $valid,
            ));

            return $req->fetch(PDO::FETCH_OBJ);
        }
        $req = $this->executeRequest($sql, array(
            'email' => $this->user->getEmail(),
        ));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    public function save(): bool
    {
        $sql = "INSERT INTO users(username, email, password, role_id, is_valid, created_at, token) VALUES(:username, :email, :password, :role_id, :is_valid, :createdAt, :token)";

        $req = $this->executeRequest($sql, array(
            'username' => $this->user->getUsername(),
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword(),
            'role_id' => $this->user->getRoleId(),
            'is_valid' => $this->user->getValid(),
            'createdAt' => $this->user->getCreatedAt(),
            'token' => $this->user->getToken(),
        ));
        return true;
    }
}