<?php

namespace Framework;

use Exception;
use Model\User;

class Session
{
    public function __construct()
    {
        if (!self::getSession()) {
            session_start();
        };
        if ($this->notExistAttribut('auth')) {
            $userAuth = new User();
            $userAuth->setRoleLevel(Controller::VISITOR);
            $this->setAttribut('auth',$userAuth);
        }
    }

    public function deconnexion()
    {
        unset($_SESSION['auth']);
        header('Location: /home');
        exit;
    }

    public function setAttribut($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function existAttribut($name)
    {
        return (isset($_SESSION[$name]) && $_SESSION[$name] != "");
    }

    public function notExistAttribut($name)
    {
        return (!isset($_SESSION[$name]));
    }

    public function getAttribut($name)
    {
        if ($this->existAttribut($name)) {
            return $_SESSION[$name];
        } else {
            throw new \Exception("Attribut absent.");
        }
    }

    public function deleteAttribut($name)
    {
        if ($this->existAttribut($name)) {
            unset($_SESSION[$name]);
        } else {
            throw new \Exception("Attribut absent.");
        }
    }

    static public function getSession()
    {
        if (isset($_SESSION)) {
            return $_SESSION['auth'];
        }
    }
}