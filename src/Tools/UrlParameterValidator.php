<?php

namespace App\Tools;

use Symfony\Component\Validator\Validation;

class UrlParameterValidator
{
    public static function validate($value, $constraints):bool
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($value, $constraints);
        if (count($violations) > 0) {
            return false;
        }
        return true;
    }
}