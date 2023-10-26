<?php

namespace Tests\Feature\Core\UseCase;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\List\ListCategoriesOutputDto;
use Tests\TestCase;

class ListCategoriesUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list_all(): void
    {
        $category = Model::factory(50)->create()->first();

        $response = $this->createUseCase();

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertEquals(50, $response->total);
        $this->assertEquals(1, $response->current_page);
        $this->assertCount(15, $response->items);
    }

    public function test_list_empty(): void
    {
        $response = $this->createUseCase();

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $response);
        $this->assertEquals(0, $response->total);
        $this->assertEquals(1, $response->current_page);
        $this->assertCount(0, $response->items);
    }

    private function createUseCase()
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoriesUseCase($repository);

        return $useCase->execute(
            new ListCategoriesInputDto()
        );
    }
}
