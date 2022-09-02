<?php


namespace Services;

use Framework\Controller;
use Model\User;
use Services\Validator;


class ValidatorAddArticle extends Validator
{
    const MAX_LENGTH = 50;
    private int $errors = 0;
    private object $article;
    private object $category;
    private array $errorsMsg = [];

    public function __construct($article, $category)
    {
        $this->article = $article;
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkTitle()
    {
        if ($this->isEmpty($this->article->getTitle())) {
            $this->errors++;
            $this->errorsMsg['title'] = "Titre de l'article vide";
        }

        if ($this->isToUpper($this->article->getTitle(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['title'] = "Titre de l'article trop long";
        }
    }

    private function checkCategory()
    {
        if ($this->isEmpty($this->category->getId())) {
            $this->errors++;
            $this->errorsMsg['category'] = "Veuillez sélectionner au minimum une categorie";
        }
    }

    private function checkAuthor()
    {
        if ($this->isEmpty($this->article->getUserId())) {
            $this->errors++;
            $this->errorsMsg['author'] = "Veuillez sélectionner un auteur";
        }
    }

    public function checkImagePresent(): bool
    {
        if ($this->article->getImageFilename() !== '') {
            return true;
        } else {
            return false;
        }
    }

    private function checkImageName()
    {
        if (Validator::isToUpper($this->article->getImageFilename(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['file'] = "Nom de l'image trop long";
        }
    }

    private function checkAltImage()
    {
        if ($this->isEmpty($this->article->getImageAlt()) ) {
            $this->errors++;
            $this->errorsMsg['imageAlt'] = "Description de l'image vide";
        }
    }

    private function checkContent()
    {
        if ($this->isEmpty($this->article->getContent())) {
            $this->errors++;
            $this->errorsMsg['content'] = "Contenu de l'article vide";
        }
    }

    private function checkExcerpt()
    {
        if ($this->isEmpty($this->article->getExcerpt())) {
            $this->errors++;
            $this->errorsMsg['excerpt'] = "Extrait de l'article vide";
        }

        if ($this->isToUpper($this->article->getExcerpt(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['title'] = "Extrait de l'article trop long";
        }
    }


    public function formAddArticleValidate(): bool
    {
        $this->checkTitle();
        $this->checkCategory();
        $this->checkContent();
        $this->checkExcerpt();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function checkAuthorSelected(): bool
    {
        $this->checkAuthor();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function checkImageUpload(): bool
    {
        $this->checkImageName();
        $this->checkAltImage();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }
}