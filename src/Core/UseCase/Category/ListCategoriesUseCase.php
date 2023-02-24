<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\List\ListCategoriesOutputDto;

class ListCategoriesUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function execute(ListCategoriesInputDto $input): ListCategoriesOutputDto
    {
        $categories = $this->repository->paginate(
            filters: $input->filters,
            order: $input->order,
            page: $input->page,
            limit: $input->limit,
        );

        return new ListCategoriesOutputDto(
            items: $categories->items(),
            total: $categories->total(),
            lastPage: $categories->lastPage(),
            firstPage: $categories->firstPage(),
            currentPage: $categories->currentPage(),
            perPage: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );
    }
}
