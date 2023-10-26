<?php

namespace Tests\Feature\Core\UseCase;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Tests\TestCase;

class ListCategoryUseCaseTest extends TestCase
{
    public function test_list_category_not_found(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Category not found in data base');
        $this->expectExceptionCode(404);

        $response = $this->createUseCase();
    }

    public function test_list_category(): void
    {
        Model::factory(5)->create()->first();
        $category = Model::first();

        $response = $this->createUseCase($category->id);
        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals($category->name, $response->name);
        $this->assertEquals($category->description, $response->description);
        $this->assertEquals($category->is_active, $response->is_active);
    }

    private function createUseCase(string $id = 'fake_id')
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoryUseCase($repository);

        return $useCase->execute(
            new CategoryInputDto(id:$id)
        );
    }
}
