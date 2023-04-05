<?php

namespace Core\UseCase\DTO\Category\Delete;

class DeleteCategoryOutputDto
{
    public function __construct(
        public bool $success,
    ) {
    }
}
