<?php

namespace Tests\Unit\App\Http\Controlers\Api;

use App\Http\Controllers\Api\CategoryController;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesOutputDto;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryControllerUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_index(): void
    {
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('get')->andReturn('');

        $mockOutputDTO = Mockery::mock(ListCategoriesOutputDto::class, [
            [], 1, 1, 1, 1, 1,
        ]);

        $mockUseCase = Mockery::mock(ListCategoriesUseCase::class);
        $mockUseCase->shouldReceive('execute')->andReturn($mockOutputDTO);

        $controller = new CategoryController();
        $response = $controller->index($mockRequest, $mockUseCase);

        $this->assertIsObject($response->resource);
        $this->assertArrayHasKey('meta', $response->additional);

        /**
         * Spies
         */
        $mockUseCaseSpy = Mockery::spy(ListCategoriesUseCase::class);
        $mockUseCaseSpy->shouldReceive('execute')->andReturn($mockOutputDTO);
        $response = $controller->index($mockRequest, $mockUseCaseSpy);

        $mockUseCaseSpy->shouldHaveReceived('execute');
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
