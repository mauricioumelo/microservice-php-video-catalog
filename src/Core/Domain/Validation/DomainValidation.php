<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
    protected static function message(string $rule, string $nameProperty)
    {
        $messages = [
            'required'=> fn ($nameProperty): string =>"field ".$nameProperty. " is required",
            'max'=> fn ($nameProperty): string => "field ".$nameProperty." has exceeded the character limit",
            'min'=> fn ($nameProperty): string => "field ".$nameProperty." does not have the minimum characters",
        ];

        return $messages[$rule];
    }

    public static function required(string $value)
    {
        if (empty($value)) {
            return true;
        }
    }

    public static function max(string $value, int $max)
    {
        if (!empty($value) && strlen($value) > $max) {
            return true;
        }
    }

   public static function min(string $value, int $min)
   {
       if (!empty($value) && strlen($value) < $min) {
           return true;
       }
   }

    public static function validate(array $data, array $properties, array $messages)
    {
        foreach ($properties as $property => $ruleProperty) {
            $rules = explode('|', $ruleProperty);

            foreach ($rules as $rule) {
                if (str_contains($rule, ':')) {
                    list($rule, $valueRule) = explode(':', $rule);

                    if (self::$rule($data[$property], $valueRule)) {
                        throw new EntityValidationException(isset($messages[$property . '.' . $rule]) ? $messages[$property . '.' . $rule] : DomainValidation::message($rule, $property));
                    }
                    continue;
                }

                if (self::$rule($data[$property])) {
                    throw new EntityValidationException($messages[$property . '.' . $rule]);
                }
            }
        }
    }
}
