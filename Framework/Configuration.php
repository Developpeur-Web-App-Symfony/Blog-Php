<?php

class Configuration
{
    private static $parameters;

    // Renvoie la valeur d'un paramètre de configuration

    /**
     * @param $nom
     * @param null $valueDefault
     * @return mixed|null |null
     * @throws Exception
     */
    public static function get($nom, $valueDefault = null)
    {
        if (isset(self::getParameters()[$nom])) {
            $value = self::getParameters()[$nom];
        } else {
            $value = $valueDefault;
        }
        return $value;
    }

    // Renvoie le tableau des paramètres en le chargeant au besoin

    /**
     * @return array|bool
     * @throws Exception
     */
    private static function getParameters()
    {
        if (self::$parameters == null) {
            $pathFile = "config/dev.ini";
            if (!file_exists($pathFile)) {
                $pathFile = "config/prod.ini";
            }
            if (!file_exists($pathFile)) {
                throw new Exception("Aucun fichier de configuration trouvé");
            } else {
                self::$parameters = parse_ini_file($pathFile);
            }
        }
        return self::$parameters;
    }
}