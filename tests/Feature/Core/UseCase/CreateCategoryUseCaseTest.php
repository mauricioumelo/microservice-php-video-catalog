<?php

namespace Tests\Feature\Core\UseCase;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\Create\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\Create\CreateCategoryOutputDto;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create(): void
    {
        $repository = new CategoryEloquentRepository(new Category());
        $useCase = new CreateCategoryUseCase($repository);

        $response = $useCase->execute(
            new CreateCategoryInputDto(
                name: 'test category name'
            )
        );

        $this->assertInstanceOf(CreateCategoryOutputDto::class, $response);
        $this->assertEquals('test category name', $response->name);
        $this->assertNotEmpty($response->id);
    }
}
