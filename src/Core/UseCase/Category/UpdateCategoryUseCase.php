<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\DTO\Category\Update\UpdateCategoryInputDto;

class UpdateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function execute(UpdateCategoryInputDto $input): CategoryOutputDto
    {
        $category = $this->repository->findById($input->id);

        $category->update(
            [
                'name' => $input->name,
                'description' => $input->description ?? $category->is_active,
                'isActive' => (bool) $input->isActive ?? $category->description
            ]
        );

        $categoryUpdate = $this->repository->update(category: $category);

        return new CategoryOutputDto(
            id: $categoryUpdate->id(),
            name: $categoryUpdate->name,
            description: $categoryUpdate->description,
            is_active: $categoryUpdate->isActive,
            created_at: $categoryUpdate->createdAt()
        );
    }
}
