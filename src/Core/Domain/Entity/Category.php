<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MagicMethodsTrait;

class Category
{
    use MagicMethodsTrait;
    public function __construct(
        protected string $name,
        protected string $id = '',
        protected string $description ='',
        protected bool $isActive = true,
    )
    {
        
    }
}