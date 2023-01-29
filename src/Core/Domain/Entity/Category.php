<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;
use Core\Domain\Exception\EntityValidationException;

class Category
{
    use MagicMethodsTrait;
    public function __construct(
        protected string $name,
        protected string $id = '',
        protected string $description ='',
        protected bool $isActive = true,
    ) {
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
        if (empty($this->name)) {
            throw new EntityValidationException('entered "name" is empty');
        }

        if (!empty($this->name) && strlen($this->name) > 255 && strlen($this->name) < 3 ) {
            throw new EntityValidationException('entered "name" has less than 3 characters');
        }

        if (!empty($this->description) && strlen($this->description) > 255 && strlen($this->description) < 10) {
            throw new EntityValidationException('entered "name" has less than 10 characters');
        }
    }
}
