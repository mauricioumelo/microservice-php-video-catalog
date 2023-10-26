<?php

namespace Tests\Domain\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\List\ListCategoriesInputDto;
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
        $this->spyRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockPaginate = Mockery::mock(stdClass::class, PaginateInterface::class);
        $this->mockDtoInput = Mockery::mock(ListCategoriesInputDto::class, []);
    }

    public function test_list_categories_empty(): void
    {
        $this->mockPaginate(
            items: [],
            total: 0,
            last_page: 1,
            first_page: 1,
            current_page: 1,
            per_page: 0,
        );

        $this->mockRepo
            ->shouldReceive('paginate')
            ->andReturn($this->mockPaginate);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $responseDto = $useCase->execute($this->mockDtoInput);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseDto);
        $this->assertCount(0, $responseDto->items);
        $this->assertEquals(1, $responseDto->last_page);
        $this->assertEquals(1, $responseDto->first_page);
        $this->assertEquals(0, $responseDto->per_page);

        /**
         * Spies
         */
        $this->spyRepo
            ->shouldReceive('paginate')
            ->andReturn($this->mockPaginate);

        $useCase = new ListCategoriesUseCase($this->spyRepo);
        $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('paginate');
    }

    public function test_list_categories(): void
    {
        $this->mockPaginate(
            items: [
                new Category(
                    'name'
                ),
                new Category(
                    'segunda'
                ),
            ],
            total: 2,
            last_page: 1,
            first_page: 1,
            current_page: 1,
            per_page: 2
        );

        $this->mockRepo
            ->shouldReceive('paginate')
            ->andReturn($this->mockPaginate);

        $useCase = new ListCategoriesUseCase($this->mockRepo);
        $responseDto = $useCase->execute($this->mockDtoInput);

        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseDto);
        $this->assertCount(2, $responseDto->items);
        $this->assertInstanceOf(Category::class, $responseDto->items[1]);
        $this->assertEquals(1, $responseDto->last_page);
        $this->assertEquals(1, $responseDto->first_page);
        $this->assertEquals(2, $responseDto->per_page);

        /**
         * Spies
         */
        $this->spyRepo
            ->shouldReceive('paginate')
            ->andReturn($this->mockPaginate);

        $useCase = new ListCategoriesUseCase($this->spyRepo);
        $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('paginate');
    }

    protected function mockPaginate(
        array $items,
        int $total,
        int $last_page,
        int $first_page,
        int $current_page,
        int $per_page,
    ) {
        $this->mockPaginate
            ->shouldReceive('items')
            ->andReturn($items);

        $this->mockPaginate
            ->shouldReceive('total')
            ->andReturn($total);

        $this->mockPaginate
            ->shouldReceive('lastPage')
            ->andReturn($last_page);

        $this->mockPaginate
            ->shouldReceive('firstPage')
            ->andReturn($first_page);

        $this->mockPaginate
            ->shouldReceive('currentPage')
            ->andReturn($current_page);

        $this->mockPaginate
            ->shouldReceive('perPage')
            ->andReturn($per_page);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
