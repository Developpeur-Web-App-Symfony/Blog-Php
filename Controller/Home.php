<?php

namespace Controller;

use Exception;
use Framework\Session;
use Model\User;
use Services\ValidatorUser;

class Home extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {


        $this->generateView([
            'person' => [
                'lastname' => 'GILLES'
            ],
            'myDog' => 'Winter',

        ]);
    }

    /**
     * @throws Exception
     */
    public function contact()
    {

        $this->generateView([

        ]);
    }

    /**
     * @throws Exception
     */
    public function signIn()
    {
        if ($this->request->existsParameter('loginForm')) {
            if ($this->request->existsParameter('loginForm') == 'login') {
                $user = new User();
                $user->setEmail($this->request->getParameter('email'));
                $user->setPassword($this->request->getParameter('password'));
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formLoginValidate()) {
                    $repositoryUser = new \Repository\User($user);
                    $userBdd = $repositoryUser->getUserInBdd(self::IS_VALID['VALID']);
                    if ($userBdd) {
                        $user->hydrate($userBdd);
                        $userRole = $user->getRoleId();
                        $validatorUser->roleBlocked($userRole);

                        if ($validatorUser->login()) {
                            $sessionAuth = new Session();
                            $sessionAuth->setAttribut('auth', $user);
                            $validatorUser->checkRoleRedirect($userRole);
                        } else {
                            $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant incorrect"]);
                        }
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant incorrect"]);
                    }
                }
            }
        }
        $this->generateView([
            'user' => $user ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        if ($this->request->existsParameter('registerForm')) {
            if ($this->request->getParameter('registerForm') == 'register') {
                $user = new User();
                $user->setUsername($this->request->getParameter('username'));
                $user->setEmail($this->request->getParameter('email'));
                $user->setpassword($this->request->getParameter('password'));
                $user->setCPassword($this->request->getParameter('cPassword'));
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formRegisterValidate()) {
                    if ($validatorUser->registerValidate()) {
                        $user->setDataNewUser();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        $repositoryUser = new \Repository\User($user);
                        $user->passwordHash();
                        if ($repositoryUser->save()) {
                            $this->sendEmail('register', 'Inscription sur le site JM Website', $user->getEmail(), $data);
                            $this->request->getSession()->setAttribut('flash', ['alert' => "Veuillez consulté votre messagerie afin de valider la création de votre compte"]);
                            header('Location: signIn');
                            exit();
                        }
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant indisponible"]);
                        header('Location: register');
                        exit();
                    }
                }
            }
        }
        $this->generateView([
            'user' => $user ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function userValidationRegistered()
    {
        if ($this->request->existsParameter('email') && $this->request->existsParameter('token')) {
            $user = new User();
            $user->setEmail($this->request->getParameter('email'));
            $user->setToken($this->request->getParameter('token'));
            $validatorUser = new ValidatorUser($user);

            if ($validatorUser->emailAndTokenValidation()) {
                $repositoryUser = new \Repository\User($user);
                $userBdd = $repositoryUser->getEmailAndTokenUserInBdd();
                if ($userBdd) {
                    $user->hydrate($userBdd);
                    $user->setValid(self::IS_VALID ['VALID']);
                    $user->setRoleId(self::USER);
                    $repositoryUser->updateUser();
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Votre compte est désormais activé, vous pouvez dès à présent vous connecter à l'aide de vos identifiants"]);
                    header('Location: home/signIn');
                    exit();
                }
            }
        } else {
            header('Location: home');
            exit();
        }
        $this->generateView([
            'user' => $user ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword()
    {
        if ($this->request->existsParameter('forgotPasswordForm')) {
            if ($this->request->existsParameter('forgotPasswordForm') == 'forgotPassword') {
                $user = new User();
                $user->setEmail($this->request->getParameter('email'));
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formForgotPasswordValidate()) {
                    $repositoryUser = new \Repository\User($user);
                    $userBdd = $repositoryUser->getUserInBdd(self::IS_VALID['VALID']);
                    if ($userBdd) {
                        $user->hydrate($userBdd);
                        $user->generateToken();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        $repositoryUser->updateToken();
                        $this->sendEmail('forgotPassword', 'Reinitialisation du mot de passe', $user->getEmail(), $data);
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Veuillez consulté votre messagerie afin de reinitialiser votre mot de passe"]);
                        header('Location: forgotPassword');
                        exit();
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Email incorrect"]);
                    }
                }
            }
        }
        $this->generateView([
            'user' => $user ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function resetPassword()
    {
        if ($this->request->existsParameter('passwordForm')) {
            if ($this->request->getParameter('passwordForm') == 'newPassword') {
                $user = new User();
                $user->setEmail($this->request->getParameter('email'));
                $user->setToken($this->request->getParameter('token'));
                $repositoryUser = new \Repository\User($user);
                $userBdd = $repositoryUser->getEmailAndTokenUserInBdd();
                if ($userBdd) {
                    $user->setpassword($this->request->getParameter('password'));
                    $user->setCPassword($this->request->getParameter('cPassword'));
                    $validatorUser = new ValidatorUser($user);
                    if ($validatorUser->formNewPasswordValidate()) {
                        $user->generateToken();
                        $user->passwordHash();
                        $repositoryUser->updatePassword();
                        $this->sendEmail('newPassword', 'Modification de votre compte sur le site JMWebsite', $user->getEmail());
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Vous pouvez dès à présent vous connecter avec votre nouveau mot de passe"]);
                        header('Location: /home/signIn');
                        exit();
                    }
                }
            }
        }
        $this->generateView([
            'user' => $user ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function disconnected()
    {
        $sessionAuth = new Session();
        $sessionAuth->deconnexion();
    }
}