<?php

namespace Tests\Domain\UseCase\Category;

use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;

class ListCategoriesUseCaseUnitTest extends TestCase
{

    protected $mockDtoInput;
    protected $spyRepo;
    protected $mockRepo;
    protected $mockPaginate;

    public function setUp(): void
    {
        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockPaginate = Mockery::mock(stdClass::class, PaginateInterface::class);
        $this->mockDtoInput = Mockery::mock(ListCategoriesInputDto::class, []);
    }

    public function test_list_categories_empty(): void
    {
        $this->mockPaginate
            ->shouldReceive('items')
            ->andReturn([]);
            
        $this->mockRepo
            ->shouldReceive('paginate')
            ->andReturn($this->mockPaginate);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $responseDto = $useCase->execute($this->mockDtoInput);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseDto);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
