<?php

namespace Core\UseCase\DTO\Category\List;

class ListCategoriesOutputDto
{
    public function __construct(
        public array $items,
        public int $total,
        public int $last_page,
        public int $first_page,
        public int $current_page,
        public int $per_page,
    ) {
    }
}
