<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $resquest, ListCategoriesUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCategoriesInputDto(
                filters:$resquest->get('filters', ''),
                order:$resquest->get('order', 'DESC'),
                page:(int) $resquest->get('page', 1),
                limit:(int) $resquest->get('limit', 15),
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
}
