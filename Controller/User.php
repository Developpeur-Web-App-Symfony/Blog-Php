<?php

namespace Controller;

use Exception;
use Framework\Controller;
use Framework\Session;
use Services\ValidatorUser;

class User extends \Framework\Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $this->generateView([
        ]);
    }

    /**
     * @throws Exception
     */
    public function userManagement()
    {
        $user = new \Model\User();
        $repositoryUser = new \Repository\User($user);
        $allUser = $repositoryUser->getAllUsers();

        $this->generateView([
            'allUsers' => $allUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function account()
    {
        $user = new \Model\User();
        $repositoryUser = new \Repository\User($user);
        $userId = $this->request->getParameter('id');
        $userBdd = $repositoryUser->getUser($userId);
        $repositoryRole = new \Repository\Role();
        $allRoles = $repositoryRole->getAllRoles();
        if ($this->request->existsParameter('userForm')) {
            if ($this->request->existsParameter('userForm') == 'update') {
                $user->setUsername($this->request->getParameter('username'));
                $user->setEmail($this->request->getParameter('email'));
                $user->setId($userId);
                $validatorUser = new ValidatorUser($user);
                if ($this->request->getParameter('role') !== null){
                    $role = implode("", $this->request->getParameter('role'));
                    $user->setRoleLevel($role);
                    if ($validatorUser->formUpdateValidateAdmin()){
                        if ($validatorUser->mailNotExistInBdd()
                        && $validatorUser->usernameNotExistInBdd()){
                            $data = [
                                'username' => $user->getUsername(),
                                'email' => $user->getEmail(),
                            ];
                            if($repositoryUser->updateAccountUser()) {
                                $this->sendEmail('updateUser', 'Modification du compte sur le site de JM Website', $user->getEmail(), $data);
                                $this->request->getSession()->setAttribut('flash', ['alert' => "Modification effectué avec succès"]);
                                header("Location: /user/account/$userId");
                                exit();
                            }
                        }
                        else{
                            $this->request->getSession()->setAttribut('flash', ['alert' => "Nom d'utilisateur ou email indisponible"]);
                            header("Location: /user/account/$userId");
                            exit();
                        }

                    }
                } else{
                    if ($validatorUser->formUpdateValidateUser()){
                        if ($validatorUser->mailNotExistInBdd()
                            && $validatorUser->usernameNotExistInBdd()){
                            $user->setRoleLevel($userBdd->getRoleLevel());
                            $data = [
                                'username' => $user->getUsername(),
                                'email' => $user->getEmail(),
                            ];
                            if($repositoryUser->updateAccountUser()) {
                                $this->sendEmail('updateUser', 'Modification du compte sur le site de JM Website', $user->getEmail(), $data);
                                $this->request->getSession()->setAttribut('flash', ['alert' => "Modification effectué avec succès"]);
                                header("Location: /user/account/$userId");
                                exit();
                            }
                        } else{
                            $this->request->getSession()->setAttribut('flash', ['alert' => "Nom d'utilisateur ou email indisponible"]);
                            header("Location: /user/account/$userId");
                            exit();
                        }


                    }
                }
            }
        }

        $this->generateView([
            'user' => $userBdd ?? null,
            'allRoles' => $allRoles ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    /**
     * @throws Exception
     */
    public function updatePassword()
    {
        $user = new \Model\User();
        $repositoryUser = new \Repository\User($user);
        $userId = $this->request->getParameter('id');
        $userBdd = $repositoryUser->getUser($userId);
        if ($this->request->existsParameter('userForm')) {
            if ($this->request->existsParameter('userForm') == 'update') {
                $user->setpassword($this->request->getParameter('password'));
                $user->setCPassword($this->request->getParameter('cPassword'));
                $user->setId($userId);
                $validatorUser = new ValidatorUser($user);
                if ($validatorUser->formNewPasswordValidate()){
                    $user->passwordHash();
                    $user->setEmail($userBdd->getEmail());
                    $repositoryUser->updateNewPassword();
                    $this->sendEmail('newPassword', 'Modification de votre compte sur le site JMWebsite', $user->getEmail());
                    $this->request->getSession()->setAttribut('flash', ['alert' => "Vous pouvez dès à présent vous connecter avec votre nouveau mot de passe"]);
                    header("Location: /user/account/$userId");
                    exit();
                }
            }
        }


        $this->generateView([
            'user' => $userBdd ?? null,
            'validator' => $validatorUser ?? null
        ]);
    }

    public function delete()
    {
        $comment = new \Model\Comment();
        $commentId = $this->request->getParameter('id');
        $comment->setId($commentId);
        $repositoryComment = new \Repository\Comment($comment);
        $repositoryComment->deleteComment();
        $this->request->getSession()->setAttribut('flash', ['alert' => "Commentaire supprimer avec succès"]);
        header('Location: /comment/commentManagement');
        exit;
    }
}