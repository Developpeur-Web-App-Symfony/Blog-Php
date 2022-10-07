<?php

namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;
use Model\User;
use Services\ValidatorContact;
use Services\ValidatorUser;

class Home extends \Framework\Controller
{

    /**
     * @throws Exception
     */
    public function index()
    {
        $repositoryArticle = new \Repository\Article();
        $allArticle = $repositoryArticle->getAllArticles(Controller::PUBLISH['PUBLISH']);

        if ($this->request->existsParameter('contactForm')) {
            if ($this->request->getParameter('contactForm') == 'contact') {
                $name = $this->request->getParameter('name');
                $email = $this->request->getParameter('email');
                $phone = $this->request->getParameter('phone');
                $content = $this->request->getParameter('content');
                $validatorContact = new ValidatorContact();
                if ($validatorContact->formContactValidate($name, $email, $content)) {
                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'content' => $content
                    ];
                    $this->sendEmail('contact', 'Formulaire de contact sur le site de JM Website', Controller::FROMEMAIL, $data);
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Votre message a bien été envoyé, nous reviendrons vers vous dans les plus brefs délais"]);
                    $this->redirect("index");
                }

            }
        }
        $this->generateView([
            'allArticle' => $allArticle ?? null,
            'validator' => $validatorContact ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function contact()
    {
        if ($this->request->existsParameter('contactForm')) {
            if ($this->request->getParameter('contactForm') == 'contact') {
                $name = $this->request->getParameter('name');
                $email = $this->request->getParameter('email');
                $phone = $this->request->getParameter('phone');
                $content = $this->request->getParameter('content');
                $validatorContact = new ValidatorContact();
                if ($validatorContact->formContactValidate($name, $email, $content)) {
                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'content' => $content
                    ];
                    $this->sendEmail('contact', 'Formulaire de contact sur le site de JM Website', Controller::FROMEMAIL, $data);
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Votre message a bien été envoyé, nous vous reviendrons vers vous dans les plus brefs délais"]);
                    $this->redirect("index");
                }

            }
        }

        $this->generateView([
            'validator' => $validatorContact ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function signIn()
    {
        if (intval(Session::getSession()->getRoleLevel()) > Controller::VISITOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès à cette page"]);
            $this->redirect("/home/index");
        }
        if ($this->request->existsParameter('loginForm')) {
            if ($this->request->getParameter('loginForm') == 'login') {
                $user = new User();
                $user->setEmail($this->request->getParameter('email'));
                $user->setPassword($this->request->getParameter('password'));
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formLoginValidate()) {
                    $repositoryUser = new \Repository\User($user);
                    $userBdd = $repositoryUser->getUserInBdd(Controller::IS_VALID['VALID']);
                    if ($userBdd) {
                        $validatorUser->getIpAdressUser();
                        $user->hydrate($userBdd);
                        $userRole = $user->getRoleLevel();
                        if ($validatorUser->login()) {
                            $repositoryUser->updateIpUser();
                            $sessionAuth = new Session();
                            $user->setCPassword(null);
                            $sessionAuth->setAttribut('auth', $user);
                            $validatorUser->checkRoleRedirect($userRole);
                        } else {
                            $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant incorrect"]);
                        }
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant incorrect ou compte désactivé"]);
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
        if (intval(Session::getSession()->getRoleLevel()) > Controller::VISITOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès à cette page"]);
            $this->redirect("/home/index");
        }
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
                            $this->redirect("signIn");
                        }
                    } else {
                        $this->request->getSession()->setAttribut('flash', ['alert' => "Identifiant indisponible"]);
                        $this->redirect("register");
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
        if (intval(Session::getSession()->getRoleLevel()) > Controller::VISITOR) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Votre compte est déjà activé"]);
            $this->redirect("/home/index");
        }
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
                    $user->setValid(Controller::IS_VALID ['VALID']);
                    $user->setRoleLevel(Controller::USER);
                    $repositoryUser->updateUser();
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Votre compte est désormais activé, vous pouvez dès à présent vous connecter à l'aide de vos identifiants"]);
                    $this->redirect("/home/signIn");
                }
            }
        } else {
            $this->redirect("/home/index");
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
            if ($this->request->getParameter('forgotPasswordForm') == 'forgotPassword') {
                $user = new User();
                $user->setEmail($this->request->getParameter('email'));
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formForgotPasswordValidate()) {
                    $repositoryUser = new \Repository\User($user);
                    $userBdd = $repositoryUser->getUserInBdd(Controller::IS_VALID['VALID']);
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
                        $this->redirect("forgotPassword");
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
        if (!$this->request->existsParameter('email') && !$this->request->existsParameter('token')) {
            $this->request->getSession()->setAttribut('flash', ['alert' => "Vous n'avez pas accès à cette page"]);
            $this->redirect("/home/index");
        }
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
                        $this->redirect("/home/signIn");
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