<?php

namespace Tests\Feature\App\Controller\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};

use Illuminate\Http\JsonResponse;
use Illuminate\Http\{
    Request,
    Response
};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;
    private CategoryController $controller;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(new Category());
        $this->controller = new CategoryController();

        parent::setUp();
    }

    public function test_index(): void
    {
        $useCase = new ListCategoriesUseCase($this->repository);

        $response = $this->controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->arrayHasKey('meta', $response->additional);
    }

    public function test_store(): void
    {
        $useCase = new CreateCategoryUseCase($this->repository);

        $request = new StoreCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'test',
            'description' => 'test description',
        ]));

        $response = $this->controller->store($request, $useCase);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
    }

    public function test_show(): void
    {
        $category = Category::factory()->create();

        $useCase = new ListCategoryUseCase($this->repository);
        $response = $this->controller->show(
            useCase: $useCase,
            id: $category->id
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    public function test_update(): void
    {
        $category = Category::factory()->create();

        $useCase = new UpdateCategoryUseCase($this->repository);
        $request = new UpdateCategoryRequest();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'name' => 'test update category',
            'description' => 'test update description',
            'isActive' => false
        ]));

        $response = $this->controller->update(
            request: $request,
            useCase: $useCase,
            id: $category->id,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertDatabaseHas('categories', [
            'name' => 'test update category',
            'description' => 'test update description',
            'is_active' => false
        ]);
    }
    public function test_destroy(): void
    {
        $category = Category::factory()->create();

        $useCase = new DeleteCategoryUseCase($this->repository);

        $response = $this->controller->destroy(id: $category->id, useCase: $useCase);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->status());
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
