<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\Delete\DeleteCategoryOutputDto;

class DeleteCategoryUseCase 
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) {
    }

    public function execute(CategoryInputDto $input): DeleteCategoryOutputDto
    {
        $category = $this->repository->findById($input->id);

        $success = $this->repository->delete(id: $category->id());

        return new DeleteCategoryOutputDto(
            success:$success
        );
    }
}
