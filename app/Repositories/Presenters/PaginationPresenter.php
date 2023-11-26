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

    public function items(): array
    {
        return collect($this->paginator->items())->map(function ($item) {
            return (object) $item->toArray();
        })->toArray();
    }

    public function totalItems(): int
    {
        return $this->paginator->total();
    }

    public function totalPages(): int
    {
        return $this->paginator->lastPage() ?? 1;
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function getPaginationInfo(): array
    {
        return [
            'has_previous_page' => $this->paginator->previousPageUrl() !== null,
            'has_next_page' => $this->paginator->nextPageUrl() !== null,
            'is_first_page' => $this->currentPage() === 1,
            'is_last_page' => $this->currentPage() === $this->totalPages(),
        ];
    }

    private function getSpecificPageLinks(): array
    {
        $links = [];
        for ($page = 1; $page <= $this->totalPages(); $page++) {
            $links["page_$page"] = $this->paginator->url($page);
        }
        return $links;
    }

    public function getLinks(): array
    {
        $links = [
            'previus_page' => $this->paginator->previousPageUrl(),
            'next_page' => $this->paginator->nextPageUrl(),
            'specific_page' => $this->getSpecificPageLinks()
        ];

        return $links;
    }
}
