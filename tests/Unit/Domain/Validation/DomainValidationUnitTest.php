<?php

namespace Tests\Unit\Domain;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationUnitTest extends TestCase
{
    public function test_not_null()
    {
        $value = '';

        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Valor informado é nulo ou vazio');

        DomainValidation::validate(['value' => ''], ['value' => 'required'], ['value.required' => 'Valor informado é nulo ou vazio']);
    }

    public function test_not_null_with_message_exception()
    {
        $value = '';

        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Valor informado é nulo ou vazio');

        DomainValidation::validate(['value' => ''], ['value' => 'required'], ['value.required' => 'Valor informado é nulo ou vazio']);
    }

    public function test_string_max_length()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Valor informado ultrapassou o limite de caracteres');

        DomainValidation::validate(['value' => 'asd'], ['value' => 'max:2'], ['value.max' => 'Valor informado ultrapassou o limite de caracteres']);
    }

    public function test_string_min_length()
    {
        $value = 'asd';

        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Valor informado deve possuir no mínimo 5 caracteres');

        DomainValidation::validate(['value' => 'asd'], ['value' => 'min:5'], ['value.min' => 'Valor informado deve possuir no mínimo 5 caracteres']);
    }

    public function test_string_can_null_max_length()
    {
        DomainValidation::validate(['value' => ''], ['value' => 'max:2'], ['value.max' => 'Valor informado ultrapassou o limite de caracteres']);

        $this->assertTrue(true);
    }
}
