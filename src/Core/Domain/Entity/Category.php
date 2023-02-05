<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Category
{
    use MagicMethodsTrait;
    public function __construct(
        protected string $name,
        protected Uuid|string $id = '',
        protected string $description ='',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = '',
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime('now');

        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    private function validate()
    {
        $rules = [
            'name'=> 'required|max:255|min:3',
            'description' => 'max:255|min:10'
        ];
        $message = [
            'name.required' => 'field name is required',
            'name.max' => 'field name has exceeded the character limit',
            'name.min' => 'field name has less than 3 characters',
            'description.max' =>'field description has exceeded the character limit',
            'description.min' =>'field description has less than 10 characters',
        ];

        DomainValidation::validate(['name' => $this->name, 'description' => $this->description], $rules, $message);
    }
}
