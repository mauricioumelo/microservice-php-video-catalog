<?php

namespace Tests\Feature\Core\UseCase;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\Delete\DeleteCategoryOutputDto;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_delete(): void
    {
        $category = Model::factory(1)->create()->first();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new DeleteCategoryUseCase($repository);

        $response = $useCase->execute(
            new CategoryInputDto(
                id: $category->id
            )
        );

        $this->assertInstanceOf(DeleteCategoryOutputDto::class, $response);
        $this->assertTrue($response->success);
        $this->assertSoftDeleted($category);
    }
}
