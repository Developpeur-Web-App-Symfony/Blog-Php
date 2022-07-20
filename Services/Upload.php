<?php
namespace Services;

use Framework\Controller;

class Upload
{
    static public function uploadPicture($value, $path)
    {
        $valueId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


        // Vérifie si le fichier a été uploadé sans erreur.
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
            $allowed = Controller::ALLOWED;
            $filename = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];

            // Vérifie l'extension du fichier
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                die("Erreur : Veuillez sélectionner un format de fichier valide.");
            }

            // Vérifie la taille du fichier - 5Mo maximum
            $maxsize = controller::MAX_SIZE;
            if ($filesize > $maxsize) {
                die("Error: La taille du fichier est supérieure à la limite autorisée.");
            }

            // Vérifie le type MIME du fichier
            if (in_array($filetype, $allowed)) {

                // Vérifie si le fichier existe avant de le télécharger.
                if (file_exists($path . $_FILES["file"]["name"])) {
                    $_SESSION['flash']['alert'] = "danger";
                    $_SESSION['flash']['infos'] = $_FILES["file"]["name"] . " existe déjà.";
                } else {
                    if (file_exists($value->getImageFilename())) {
                        unlink($value->getImageFilename());
                    }
                    move_uploaded_file($_FILES["file"]["tmp_name"], $path . $valueId . "." . $ext);

                    $value->setImageFilename($path . $valueId . "." . $ext);
                }
            } else {
                $_SESSION['flash']['alert'] = "danger";
                $_SESSION['flash']['infos'] = "Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
            }
        } else {
            $_SESSION['flash']['alert'] = "danger";
            $_SESSION['flash']['infos'] = "Erreur " . $_FILES["file"]["error"];
        }
    }
}