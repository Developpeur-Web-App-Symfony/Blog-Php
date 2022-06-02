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
                    $userBdd = $user->getUserInBdd(self::IS_VALID['VALID']);

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
                $validatorUser = new ValidatorUser();
                if ($validatorUser->formRegisterValidate($user->getUsername(),User::MAX_LENGTH_USERNAME, $user->getEmail(), $user->getPassword(), $user->getCPassword())) {
                    if ($user->registerValidate()) {
                        $user->setDataNewUser();
                        $data = [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'token' => $user->getToken()
                        ];
                        if ($user->save()) {
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

        $user = new User();
        $get = $_GET ?? false;

        $user->setEmail($get['email']);
        $user->setToken($get['token']);
        $validatorUser = new ValidatorUser();

        $userEmail = $user->getEmail();
        if ($validatorUser->emailAndTokenValidation($user->getEmail(), $user->getToken())) {

            $userBdd = $user->getEmailAndTokenUserInBdd($userEmail);

            if ($userBdd) {
                $user->hydrate($userBdd);
                $user->setValid(self::IS_VALID ['VALID']);
                $user->setRoleId(self::USER);

                $user->updateUser();
                $this->request->getSession()->setAttribut('flash', ['alert' => "Votre compte est désormais activé, vous pouvez dès à présent vous connecter à l'aide de vos identifiants"]);
                header('Location: home/signIn');
                exit();
            }
        }
        $this->generateView([
            'userBdd' => $userBdd
        ]);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword()
    {

        $this->generateView([

        ]);
    }

}