<?php
namespace Framework;

use Exception;
use Framework\Session;
class Request
{
    // paramètres de la requête
    private array $parameters;
    private Session $session;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
        $this->session = new Session();
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    // Renvoie vrai si le paramètre existe dans la requête
    public function existsParameter($nom): bool
    {
        return (isset($this->parameters[$nom]) && ($this->parameters[$nom] != ""));
    }

    // Renvoie la valeur du paramètre demandé
    // Lève une exception si le paramètre est introuvable
    public function getParameter($nom)
    {
        if ($this->existsParameter($nom)) {
            return $this->parameters[$nom];
        } else
            return null;
    }
}