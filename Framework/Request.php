<?php

class Request
{
    // paramètres de la requête
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    // Renvoie vrai si le paramètre existe dans la requête
    public function existsParameter($nom)
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
            throw new Exception("Paramètre '$nom' absent de la requête");
    }
}