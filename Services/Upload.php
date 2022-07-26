<?php
namespace Services;

use Framework\Controller;
use Framework\Request;

class Upload
{
    static public function uploadPicture($value, $path)
    {
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
                    $_SESSION['flash']['alert'] = "danger" . $_FILES["file"]["name"] . " existe déjà.";
                    $_SESSION['flash']['infos'] = $_FILES["file"]["name"] . " existe déjà.";
                } else {
                    if (file_exists($value->getImageFilename())) {
                        unlink($value->getImageFilename());
                    }
                    $filename = substr(md5(session_id().microtime()),-12);
                    move_uploaded_file($_FILES["file"]["tmp_name"],$path . $filename . "." . $ext);
                    $value->setImageFilename($filename . "." . $ext);
                }
            } else {
                $_SESSION['flash']['alert'] = "danger Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
                $_SESSION['flash']['infos'] = "Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
            }
        } else {
            $_SESSION['flash']['alert'] = "danger Erreur " . $_FILES["file"]["error"];
            $_SESSION['flash']['infos'] = "Erreur " . $_FILES["file"]["error"];
        }
    }
}