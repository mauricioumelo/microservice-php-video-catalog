<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    public function __construct(
        protected Model $model
    ) {
    }

    public function get(string $filters = '', string $order = 'desc'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filters) {
                if (! empty($filters)) {
                    $query->orWhere('name', 'LIKE', "%{$filters}%")
                        ->orWhere('description', 'LIKE', "%{$filters}%");
                }
            })
            ->orderBy('id', $order)->get();
        $response = [];

        $categories->each(function ($item) use (&$response) {
            $response[] = $this->toCategory($item);
        });

        return $response;
    }

    public function paginate(string $filters = '', string $order = 'desc', int $page = 1, int $limit = 15): PaginateInterface
    {
        $categories = $this->model
            ->where(function ($query) use ($filters) {
                if (! empty($filters)) {
                    $query->orWhere('name', 'LIKE', "%{$filters}%")
                        ->orWhere('description', 'LIKE', "%{$filters}%");
                }
            })
            ->orderBy('id', $order)->paginate(perPage:$limit, page:$page);

        return new PaginationPresenter(paginator: $categories);
    }

    public function create(Category $category): Category
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): Category
    {
        if (! $category = $this->model->find($id)) {
            throw new NotFoundException('Category not found in data base', 404);
        }

        return $this->toCategory($category);
    }

    public function update(Category $category): Category
    {
        $model = $this->model->find($category->id());
        
        $model->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($model);
    }

    public function delete(string $id): bool
    {
        return $this->model->find($id)->delete();
    }

    public function toCategory(object $data): Category
    {
        return new Category(
            id: $data->id,
            name: $data->name,
            description: $data->description,
            isActive: $data->is_active,
            createdAt: $data->created_at,
        );
    }
}
