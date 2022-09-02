<?php

namespace Services;

class Validator
{
    public function isEmpty($value): bool
    {
        if (empty($value)) {
            return true;
        }
        return false;
    }

    public function isInteger($value): bool
    {
        if (is_integer($value)){
            return true;
        }elseif ($value === ""){
            $value = null;
        }
        return false;
    }

    public function isNotIdentic($value1, $value2): bool
    {
        if ($value1 !== $value2) {
            return true;
        }
        return false;
    }

    public function isNotAnEmail($value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function isToUpper($value, $number): bool
    {
        if (strlen($value) > $number) {
            return true;
        }
        return false;
    }
}