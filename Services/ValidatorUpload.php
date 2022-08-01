<?php


namespace Services;

use Framework\Controller;
use Framework\Request;
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
        $request = new Request($_FILES);
        $file = $request->getParameter('file');
        $fileError = $request->getParameter('file')['error'];
        if (isset($file) && $fileError !== 0) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur " . $fileError;
        } else{
            return true;
        }
    }

    private function checkExtensionFile()
    {
        $request = new Request($_FILES);
        $fileName = $request->getParameter('file')['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, Controller::ALLOWED)) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur : Veuillez sélectionner un format de fichier valide.";
        }
    }

    // Vérifie la taille du fichier - 5Mo maximum
    private function checkSizeFile()
    {
        $request = new Request($_FILES);
        $fileSize = $request->getParameter('file')['size'];
        if ($fileSize > controller::MAX_SIZE) {
            $this->errors++;
            $this->errorsMsg['file'] = "Erreur : La taille du fichier est supérieure à la limite autorisée.";
        }
    }

    // Vérifie le type MIME du fichier
    private function checkMimeFile()
    {
        $request = new Request($_FILES);
        $fileType = $request->getParameter('file')['type'];
        if (!in_array($fileType, Controller::ALLOWED)) {
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
        $request = new Request($_FILES);
        $fileTmp = $request->getParameter('file')['tmp_name'];
        $fileName = $request->getParameter('file')['name'];
        $filename = substr(md5(session_id().microtime()),-12);
        move_uploaded_file($fileTmp,Controller::PATH_UPLOAD . $filename . "." . pathinfo($fileName, PATHINFO_EXTENSION));
        $this->article->setImageFilename($filename . "." . pathinfo($fileName, PATHINFO_EXTENSION));
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