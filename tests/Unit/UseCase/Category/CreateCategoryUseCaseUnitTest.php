<?php

namespace Tests\Domain\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\Create\CreateCategoryInputDto;
use Core\UseCase\DTO\Category\Create\CreateCategoryOutputDto;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
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
        $categoryName = 'name category films';

        $this->mockEntity = Mockery::mock(Category::class, [
            $categoryName,
            $this->uuid,
        ]);

        $this->mockDtoInput = Mockery::mock(CreateCategoryInputDto::class, [
            $categoryName,
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepo = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
    }

    public function test_create_new_category()
    {
        $this->mockEntity->shouldReceive('id')->andReturn($this->uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn($this->date);

        $this->mockRepo->shouldReceive('create')->andReturn($this->mockEntity);
        $this->spyRepo->shouldReceive('create')->andReturn($this->mockEntity);

        $useCase = new CreateCategoryUseCase($this->mockRepo);

        $responseDto = $useCase->execute($this->mockDtoInput);

        $this->assertInstanceOf(CreateCategoryOutputDto::class, $responseDto);
        $this->assertTrue(true);

        $useCase = new CreateCategoryUseCase($this->spyRepo);

        $responseDto = $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('create')->once();
    }
}
