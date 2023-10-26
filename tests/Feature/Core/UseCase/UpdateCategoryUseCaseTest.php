<?php

namespace Tests\Feature\Core\UseCase;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\DTO\Category\Update\UpdateCategoryInputDto;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function test_update(): void
    {
        Model::factory(5)->create()->first();
        $category = Model::first();
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);

        $response = $useCase->execute(
            new UpdateCategoryInputDto(
                id:$category->id,
                data:[
                    'name'=> 'alter name',
                    'description'=> 'alter description',
                ]
            )
        );

        $this->assertInstanceOf(CategoryOutputDto::class, $response);
        $this->assertEquals('alter name', $response->name);
        $this->assertEquals('alter description', $response->description);
        $this->assertEquals('true', $response->is_active);
    }
}
