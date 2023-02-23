<?php

namespace Core\UseCase\DTO\Category\Create;

class CreateCategoryInputDto
{
    public function __construct(
        public string $name,
        public string $description = '',
        public bool $isActive = true,
    ) {
    }
}