<?php


namespace Services;

use Framework\Controller;
use Model\User;
use Services\Validator;

class ValidatorUser extends Validator
{
    const MAX_LENGTH_USERNAME = 16;

    private int $errors = 0;
    private object $user;
    private array $errorsMsg = [];

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkUsername()
    {
        if ($this->isEmpty($this->user->getUsername())) {
            $this->errors++;
            $this->errorsMsg['username'] = "Champ 'nom d'utilisateur' vide";
        }

        if ($this->isToUpper($this->user->getUsername(), self::MAX_LENGTH_USERNAME)) {
            $this->errors++;
            $this->errorsMsg['firstname'] = "Champ 'nom d'utilisateur' trop long";
        }
    }

    private function checkEmail()
    {
        if ($this->isEmpty($this->user->getEmail())) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email vide";
        }

        if ($this->isNotAnEmail($this->user->getEmail())) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email non valide";
        }
    }

    private function checkPassword()
    {
        if ($this->isEmpty($this->user->getPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Mot de passe vide";
        } elseif (
            $this->isEmpty($this->user->getCPassword())
            || $this->isNotIdentic($this->user->getPassword(), $this->user->getCPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Les mots de passe ne sont pas identiques";
        }
    }

    public function formRegisterValidate(): bool
    {
        $this->checkUsername();
        $this->checkEmail();
        $this->checkPassword();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function checkToken()
    {
        if ($this->isEmpty($this->user->getToken())) {
            $this->errors++;
            $this->errorsMsg['token'] = "Token non valide";
        }
    }

    public function emailAndTokenValidation(): bool
    {
        $this->checkEmail();
        $this->checkToken();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formLoginValidate(): bool
    {
        $this->checkEmail();
        $this->checkPasswordLogin();

        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    private function checkPasswordLogin()
    {
        if ($this->isEmpty($this->user->getPassword())) {
            $this->errors++;
            $this->errorsMsg['password'] = "Password vide";
        }
    }

    public function roleBlocked($userRole){
        if ($userRole == Controller::BANNED) {
            $this->errors++;
            $this->errorsMsg['blocked'] = "Connexion impossible";
        }
    }

    public function login(): bool
    {
        if (password_verify($this->user->getPassword(), $this->user->getCPassword())) {
            return true;
        } else {
            return false;
        }
    }

    public function checkRoleRedirect($userRole){
        if ($userRole == Controller::ADMIN){
            header('Location: /Dashboard/index');
            exit();
        }
        if ($userRole == Controller::USER){
            header('Location: /Dashboard/user');
            exit();
        } elseif ($userRole == Controller::VISITOR){
            $this->errors++;
            $this->errorsMsg['message'] = "Veuillez valider votre adresse email afin d'acceder a toutes les fonctionnalités";
            header('Location: login');
            exit();
        }
    }

}