<?php

namespace App\Repositories\Presenters;

use Core\Domain\Repository\PaginateInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use stdClass;

class PaginationPresenter implements PaginateInterface
{
    public function __construct(
        protected LengthAwarePaginator $paginator
    ) {
    }

    /**
     * @return stdClass[]
     */
    public function items(): array
    {
        return collect($this->paginator->items())->map(function($item){
            return (object)$item->toArray();
        })->toArray();
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function firstPage(): int
    {
        return $this->paginator->firstItem();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }
}
