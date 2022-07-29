<?php


namespace Services;

use Framework\Controller;
use Model\User;
use Services\Validator;


class ValidatorUpload extends Validator
{
    private int $errors = 0;
    private object $article;
    private array $errorsMsg = [];
    public function __construct($article)
    {
        $this->article = $article;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    // Vérifie si le fichier a été uploadé sans erreur.
    public function checkErrorFile(): bool
    {
        if (isset($_FILES["file"]) && $_FILES["file"]["error"] !== 0) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur " . $_FILES["file"]["error"];
        } else{
            return true;
        }
    }

    private function checkExtensionFile()
    {
        $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        if (!array_key_exists($ext, Controller::ALLOWED)) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur : Veuillez sélectionner un format de fichier valide.";
        }
    }

    // Vérifie la taille du fichier - 5Mo maximum
    private function checkSizeFile()
    {
        if ($_FILES["file"]["size"] > controller::MAX_SIZE) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur : La taille du fichier est supérieure à la limite autorisée.";
        }
    }

    // Vérifie le type MIME du fichier
    private function checkMimeFile()
    {
        if (!in_array($_FILES["file"]["type"], Controller::ALLOWED)) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur : Veuillez sélectionner un format de fichier valide.";
        }
    }

    // Vérifie si le fichier existe avant de le telecharger
    private function checkExistFile()
    {
        if (file_exists($this->article->getImageFilename())) {
            unlink($this->article->getImageFilename());
        }
    }

    public function uploadFile()
    {
        $filename = substr(md5(session_id().microtime()),-12);
        move_uploaded_file($_FILES["file"]["tmp_name"],Controller::PATH_UPLOAD . $filename . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
        $this->article->setImageFilename($filename . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    }

    public function formUploadValidate(): bool
    {
        $this->checkExtensionFile();
        $this->checkSizeFile();
        $this->checkMimeFile();
        $this->checkExistFile();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }
}