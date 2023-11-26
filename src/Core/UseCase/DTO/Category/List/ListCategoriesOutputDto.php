<?php

namespace Core\UseCase\DTO\Category\List;

class ListCategoriesOutputDto
{
    public function __construct(
        public array $items,
        public int $current_page,
        public array $links,
        public array $pagination_info,
        public int $per_page,
        public int $total_items,
        public int $total_pages,
    ) {
    }
}
