<?php

namespace Core\Domain\Repository;

interface PaginateInterface
{
    /**
     * @return stdClass[]
     */
    public function items(): array;

    public function totalItems(): int;

    public function totalPages(): int;

    public function currentPage(): int;

    public function perPage(): int;

    public function getPaginationInfo(): array;

    public function getLinks(): array;
}
