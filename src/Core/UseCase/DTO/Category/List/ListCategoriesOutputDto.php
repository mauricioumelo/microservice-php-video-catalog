<?php

namespace Core\UseCase\DTO\Category\List;

use Core\Domain\Repository\PaginateInterface;

class ListCategoriesOutputDto
{
    public function __construct(
        protected array $items,
        protected int $total,
        protected int $lastPage,
        protected int $firstPage,
        protected int $currentPage,
        protected int $perPage,
        protected int $to,
        protected int $from,
    ) {
    }
}
