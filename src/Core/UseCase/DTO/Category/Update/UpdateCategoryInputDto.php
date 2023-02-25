<?php

namespace Core\UseCase\DTO\Category\Update;

use Core\Domain\Entity\Category;

class UpdateCategoryInputDto
{
    public function __construct(
       public string $id,
       public array $data
    )
    {
    }
}