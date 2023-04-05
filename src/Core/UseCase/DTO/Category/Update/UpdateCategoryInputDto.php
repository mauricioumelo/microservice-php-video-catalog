<?php

namespace Core\UseCase\DTO\Category\Update;

class UpdateCategoryInputDto
{
    public function __construct(
       public string $id,
       public array $data
    ) {
    }
}
