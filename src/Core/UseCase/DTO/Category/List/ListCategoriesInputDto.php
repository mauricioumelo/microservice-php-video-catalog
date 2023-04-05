<?php

namespace Core\UseCase\DTO\Category\List;

class ListCategoriesInputDto
{
    public function __construct(
        public string $filters = '',
        public string $order = 'desc',
        public int $page = 1,
        public int $limit = 15
    ) {
    }
}
