<?php

class View
{

    // Nom du fichier associé à la vue
    private $file;
    // Titre de la vue (défini dans le fichier vue)
    private $title;

    public function __construct($action, $controller = "")
    {
        // Détermination du nom du fichier vue à partir de l'action et du constructeur
        $file = "View/";
        if ($controller != "") {
            $file = $file . $controller . "/";
        }
        $this->file = $file . $action . ".php";
    }

    // Génère et affiche la vue

    /**
     * @param $data
     * @throws Exception
     */
    public function generate($data)
    {
        // Génération de la partie spécifique de la vue
        $content = $this->generateFile($this->file, $data);
        // On définit une variable locale accessible par la vue pour la racine Web
        // Il s'agit du chemin vers le site sur le serveur Web
        // Nécessaire pour les URI de type controleur/action/id

        $webRoot = Configuration::get("webRoot", "/");
        // Génération du gabarit commun utilisant la partie spécifique
        $view = $this->generateFile('View/gabarit.php',
            array('title' => $this->title, 'content' => $content,
                'webRoot' => $webRoot));
        // Renvoi de la vue générée au navigateur
        echo $view;
    }

    public function generateMail($data)
    {
        // Génération de la partie spécifique de la vue
        $content = $this->generateFile($this->file, $data);
        // On définit une variable locale accessible par la vue pour la racine Web
        // Il s'agit du chemin vers le site sur le serveur Web
        // Nécessaire pour les URI de type controleur/action/id

        $webRoot = Configuration::get("webRoot", "/");
        // Génération du gabarit commun utilisant la partie spécifique
        $view = $this->generateFile('View/Mails/gabarit.php',
            array('title' => $this->title, 'content' => $content,
                'webRoot' => $webRoot));
        // Renvoi de la vue générée au navigateur
        return $view;
    }

    // Génère un fichier vue et renvoie le résultat produit

    /**
     * @param $file
     * @param $data
     * @return false|string
     * @throws Exception
     */
    private function generateFile($file, $data)
    {
        if (file_exists($file)) {
            // Rend les éléments du tableau $donnees accessibles dans la vue
            extract($data);
            // Démarrage de la temporisation de sortie
            ob_start();
            // Inclut le fichier vue
            // Son résultat est placé dans le tampon de sortie
            require $file;
            // Arrêt de la temporisation et renvoi du tampon de sortie
            return ob_get_clean();
        } else {
            throw new Exception("Fichier '$file' introuvable");
        }
    }

    // Nettoie une valeur insérée dans une page HTML
    private function clean($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

}
