<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function get(string $filters = '', string $order = 'desc'): array;
    public function paginate(string $filters = '', string $order = 'desc', int $page = 1, int $limit = 15): array;
    public function create(Category $category): Category;
    public function findById(string $id): Category;
    public function update(string $id, Category $category): bool;
    public function delete(string $id): bool;
    public function toCategory(object $data): Category;
}