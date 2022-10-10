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
        $this->parameters = $this->clean($parameters);
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

    // Nettoie une valeur insérée dans une page HTML
    public function clean($data) {
        if (is_array($data)) {
            foreach ( $data as $key => $value ) {
                $data[htmlspecialchars($key)] = $this->clean($value);
            }
        } else if (is_object($data)) {
            $values = get_class_vars(get_class($data));
            foreach ( $values as $key => $value ) {
                $data->{htmlspecialchars($key)} = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data);
        }
        return $data;
    }
}