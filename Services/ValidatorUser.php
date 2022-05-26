<?php


namespace Services;

use Model\User;
use Services\Validator;

class ValidatorUser extends Validator
{
    private int $errors = 0;
    private array $errorsMsg = [];

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkUsername($username, $number)
    {
        if ($this->isEmpty($username)) {
            $this->errors++;
            $this->errorsMsg['username'] = "Champ 'nom d'utilisateur' vide";
        }

        if ($this->isToUpper($username, User::MAX_LENGTH_USERNAME)) {
            $this->errors++;
            $this->errorsMsg['firstname'] = "Champ 'nom d'utilisateur' trop long";
        }
    }

    private function checkEmail($email)
    {
        if ($this->isEmpty($email)) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email vide";
        }

        if ($this->isNotAnEmail($email)) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email non valide";
        }
    }

    private function checkPassword($password, $cPassword)
    {
        if ($this->isEmpty($password)) {
            $this->errors++;
            $this->errorsMsg['password'] = "Mot de passe vide";
        } elseif (
            $this->isEmpty($cPassword)
            || $this->isNotIdentic($password, $cPassword)) {
            $this->errors++;
            $this->errorsMsg['password'] = "Les mots de passe ne sont pas identiques";
        }
    }

    public function formRegisterValidate($username, $number, $email, $password, $cPassword): bool
    {
        $this->checkUsername($username, $number);
        $this->checkEmail($email);
        $this->checkPassword($password, $cPassword);
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function checkToken($token)
    {
        if ($this->isEmpty($token)) {
            $this->errors++;
            $this->errorsMsg['token'] = "Token non valide";
        }
    }

    public function emailAndTokenValidation($email, $token)
    {
        $this->checkEmail($email);
        $this->checkToken($token);
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

}