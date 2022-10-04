<?php


namespace Services;

use Framework\Controller;
use Framework\Session;
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

    private function checkRole()
    {
        if ($this->isEmpty($this->user->getRoleLevel())) {
            $this->errors++;
            $this->errorsMsg['role'] = "Veuillez sélectionner au minimum une categorie";
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

    public function formUpdateValidateAdmin(): bool
    {
        $this->checkUsername();
        $this->checkEmail();
        $this->checkRole();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formUpdateValidateUser(): bool
    {
        $this->checkUsername();
        $this->checkEmail();
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

    public function formForgotPasswordValidate(): bool
    {
        $this->checkEmail();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formNewPasswordValidate(): bool
    {
        $this->checkPassword();
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
            header('Location: /dashboard/index');
            exit();
        }
        if ($userRole == Controller::USER){
            header('Location: /home/index');
            exit();
        }
        if ($userRole == Controller::AUTHOR){
            header('Location: /dashboard/index');
            exit();
        }
        if ($userRole == Controller::BANNED){
            header('Location: /home/index');
            exit();
        } elseif ($userRole == Controller::VISITOR){
            $this->errors++;
            $this->errorsMsg['message'] = "Veuillez valider votre adresse email afin d'acceder a toutes les fonctionnalités";
            header('Location: /home/login');
            exit();
        }
    }

    public function registerValidate(): bool
    {
        $repoUser = new \Repository\User($this->user);
        if ($repoUser->checkEmailInBdd() === 0) {
            return true;
        }
        return false;
    }

    public function mailNotExistInBdd(): bool
    {
        $repoUser = new \Repository\User($this->user);
        if ($repoUser->checkOtherMailInBdd() === 0){
            return true;
        }
        return false;
    }

    public function usernameNotExistInBdd(): bool
    {
        $repoUser = new \Repository\User($this->user);
        if ($repoUser->checkUsernameInBdd() === 0){
            return true;
        }
        return false;
    }

    public function getIpAdressUser()
    {
        // si l'ip provient du partage Internet
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $this->user->setIp($_SERVER['HTTP_CLIENT_IP']);
            //si l'ip vient du proxy
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $this->user->setIp($_SERVER['HTTP_X_FORWARDED_FOR']);
            //si l'ip provient de l'adresse distante
        } else {
            $this->user->setIp($_SERVER['REMOTE_ADDR']);
        }
        return $this->user->getIp();
    }

    public function checkIpUserIsIdentic() {
        if ($this->isNotIdentic($this->user->getIp(),$this->getIpAdressUser())) {
            header('Location: /home/disconnected');
            return false;
        }
        return true;
    }
}