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
                    'current_page' => $response->current_page,
                    'links' => $response->links,
                    'pagination_info' => $response->pagination_info,
                    'per_page' => $response->per_page,
                    'total_items' => $response->total_items,
                    'total_pages' => $response->total_pages,
                ],
            ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase): JsonResponse
    {
        $response = $useCase->execute(
            input: new CreateCategoryInputDto(
                name: $request->name,
                description: $request->description ?? '',
                isActive: $request->is_active ?? true
            )
        );

        return (new CategoryResource($response))
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

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $useCase, string $id): JsonResponse
    {
        $response = $useCase->execute(
            input: new UpdateCategoryInputDto(
                id: $id,
                name: $request->name,
                description: $request->description ?? null,
                isActive: $request->is_active
            )
        );

        return (new CategoryResource($response))
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
