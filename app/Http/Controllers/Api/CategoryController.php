<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};

use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\{
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\Create\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
use Core\UseCase\DTO\Category\Update\UpdateCategoryInputDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\{
    Request,
    Response
};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $useCase): AnonymousResourceCollection
    {
        $response = $useCase->execute(
            input: new ListCategoriesInputDto(
                filters: $request->get('filters', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                limit: (int) $request->get('limit', 15),
            )
        );

        return CategoryResource::collection(
            collect($response->items)
        )
            ->additional([
                'meta' => [
                    'total' => $response->total,
                    'last_page' => $response->last_page,
                    'first_page' => $response->first_page,
                    'current_page' => $response->current_page,
                    'per_page' => $response->per_page,
                ],
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase): JsonResponse
    {
        $response = $useCase->execute(
            input: new CreateCategoryInputDto(
                name: $request->name,
                description: (string) $request->description ?? '',
                isActive: (bool) $request->isActive ?? true
            )
        );

        return (new CategoryResource(collect($response)))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $useCase, string $id): JsonResponse
    {
        $category = $useCase->execute(
            input: new CategoryInputDto(
                id: $id
            )
        );

        return (new CategoryResource(collect($category)))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, string $id): JsonResponse
    {
        $response = $useCase->execute(
            input: new UpdateCategoryInputDto(
                id: $id,
                name: $request->name,
                description: (string) $request->description ?? '',
                isActive: (bool) $request->isActive ?? true

            )
        );

        return (new CategoryResource(collect($response)))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(string $id, DeleteCategoryUseCase $useCase): Response
    {
        $response = $useCase->execute(
            input: new CategoryInputDto(id: $id)
        );

        return response()->noContent();
    }
}
