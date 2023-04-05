<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\Create\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\Create\CreateCategoryOutputDto;

class CreateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function execute(CreateCategoryInputDto $input): CreateCategoryOutputDto
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive,
        );

        $newCategory = $this->repository->create($category);

        return new CreateCategoryOutputDto(
            id:$newCategory->id(),
            name: $newCategory->name,
            description: $newCategory->description,
            isActive: $newCategory->isActive,
            createdAt: $newCategory->createdAt()
        );
    }
}
