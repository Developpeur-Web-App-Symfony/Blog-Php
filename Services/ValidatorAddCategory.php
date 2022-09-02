<?php


namespace Services;

use Framework\Controller;
use Model\Category;
use Model\User;
use Services\Validator;


class ValidatorAddCategory extends Validator
{
    const MAX_LENGTH = 50;
    private int $errors = 0;
    private object $category;
    private array $errorsMsg = [];

    public function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkName()
    {
        if ($this->isEmpty($this->category->getName())) {
            $this->errors++;
            $this->errorsMsg['name'] = "Nom de la catégorie vide";
        }

        if ($this->isToUpper($this->category->getName(), self::MAX_LENGTH)) {
            $this->errors++;
            $this->errorsMsg['name'] = "Nom de la catégorie trop long";
        }
    }

    public function formAddCategoryValidate(): bool
    {
        $this->checkName();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }

    public function categoryNameValidate(): bool
    {
        $repositoryCategory = new \Repository\Category($this->category);
        if ($repositoryCategory->checkCategoryNameInBdd() === 0) {
            return true;
        }
        return false;
    }
}