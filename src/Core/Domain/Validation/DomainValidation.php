<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    public static function required(string $value)
    {
        if (empty($value)) {
            return true;
        }
    }

    public static function max(string $value, int $max)
    {
        if (strlen($value) > $max) {
            return true;
        }
    }

   public static function min(string $value, int $min)
   {
       if (strlen($value) < $min) {
           return true;
       }
   }

    public static function validate(array $data, array $propretys, array $messages)
    {
        foreach ($propretys as $proprety => $ruleProprety) {
            $rules = explode('/', $ruleProprety);

            foreach ($rules as $rule) {
                if (str_contains($rule, ':')) {
                    list($rule, $valueRule) = $rule;

                    if (self::$rule($data[$proprety], $valueRule)) {
                        throw new EntityValidationException($messages[$proprety . '.' . $rule]);
                    }
                    continue;
                }

                if (self::$rule($data[$proprety])) {
                    throw new EntityValidationException($messages[$proprety . '.' . $rule]);
                }
            }
        }
    }
}
