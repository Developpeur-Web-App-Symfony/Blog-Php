<?php


namespace Services;

use Framework\Controller;
use Services\Validator;


class ValidatorAddComment extends Validator
{
    private int $errors = 0;
    private object $comment;
    private array $errorsMsg = [];

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkUserId()
    {
        if ($this->isEmpty($this->comment->getUserId())) {
            $this->errors++;
            $this->errorsMsg['name'] = "Nom de la catégorie vide";
        }
    }

    public function formAddCommentValidate(): bool
    {
        $this->checkUserId();
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }
}