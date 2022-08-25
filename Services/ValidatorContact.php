<?php


namespace Services;

use Framework\Controller;
use Model\User;
use Services\Validator;


class ValidatorContact extends Validator
{
    const MAX_LENGTH_USERNAME = 16;

    private int $errors = 0;
    private array $errorsMsg = [];

    /**
     * @return array
     */
    public function getErrorsMsg(): array
    {
        return $this->errorsMsg;
    }

    private function checkUsername($name)
    {
        if ($this->isEmpty($name)) {
            $this->errors++;
            $this->errorsMsg['name'] = "Champ 'Nom' vide";
        }
    }

    private function checkEmail($email)
    {
        if ($this->isEmpty($email)) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email vide";
        }

        if ($this->isNotAnEmail($email)) {
            $this->errors++;
            $this->errorsMsg['email'] = "Email non valide";
        }
    }

    private function checkContentMessage($content)
    {
        if ($this->isEmpty($content)) {
            $this->errors++;
            $this->errorsMsg['content'] = "Champ 'Message' vide";
        }
    }

    public function formContactValidate($name, $email, $content): bool
    {
        $this->checkUsername($name);
        $this->checkEmail($email);
        $this->checkContentMessage($content);
        if ($this->errors !== 0) {
            return false;
        } else {
            return true;
        }
    }
}