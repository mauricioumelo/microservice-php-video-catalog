<?php

namespace Tests\Domain\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\DTO\Category\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\CreateCategoryOutputDto;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{
    protected $mockEntity;
    protected $mockDtoInput;
    protected $spyRepo;
    protected $mockRepo;
    protected $uuid;
    protected $date;

    public function setUp(): void
    {
        $this->uuid = (string) Uuid::uuid4()->toString();
        $this->date = new DateTime();
        $this->date = $this->date->format('Y-m-d H::i:s');

        $categoryName = 'name category select by id';

        $this->mockEntity = Mockery::mock(Category::class, [
            $categoryName,
            $this->uuid,
        ]);

        $this->mockDtoInput = Mockery::mock(CategoryInputDto::class, [
            $this->uuid
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepo = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
    }

    public function test_list_by_id_category()
    {
        $this->mockEntity->shouldReceive('id')->andReturn($this->uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn($this->date);

        $this->mockRepo->shouldReceive('findById')
            ->with($this->uuid)
            ->andReturn($this->mockEntity);

        $useCase = new ListCategoryUseCase($this->mockRepo);

        $responseDto = $useCase->execute($this->mockDtoInput);

        $this->assertInstanceOf(CategoryOutputDto::class, $responseDto);


        $this->spyRepo->shouldReceive('findById')
            ->with($this->uuid)
            ->andReturn($this->mockEntity);
        $useCase = new ListCategoryUseCase($this->spyRepo);

        $responseDto = $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('findById');
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}