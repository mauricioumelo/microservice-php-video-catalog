<?php

namespace Tests\Domain\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\UpdateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\DTO\Category\Update\UpdateCategoryInputDto;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    protected $mockDtoInput;

    protected $spyRepo;

    protected $mockRepo;

    protected $mockEntity;

    protected $mockEntityUpdated;

    protected $uuid;

    protected $created_at;

    public function setUp(): void
    {
        $this->uuid = (string) Uuid::uuid4()->toString();
        $this->created_at = new DateTime('now');
        $this->created_at = $this->created_at->format('Y-m-d H:i:s');
        $this->mockEntity = Mockery::mock(Category::class, [
            'TEST UPDATE CATEGORY',
            $this->uuid,
            '',
            true,
            $this->created_at,
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
    }

    public function test_update_category(): void
    {
        $this->mockEntity->shouldReceive('id')->andReturn($this->uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn($this->created_at);

        $this->mockEntityUpdated = Mockery::mock(Category::class, [
            'Category updated',
            $this->mockEntity->id(),
            'description updated',
            $this->mockEntity->isActive,
            $this->mockEntity->createdAt(),
        ]);

        $this->mockEntityUpdated->shouldReceive('id')->andReturn($this->uuid);
        $this->mockEntityUpdated->shouldReceive('createdAt')->andReturn($this->created_at);

        $this->mockRepo
            ->shouldReceive('findById')
            ->andReturn($this->mockEntity);

        $this->mockEntity
            ->shouldReceive('update')
            ->andReturn($this->mockEntityUpdated);

        $this->mockRepo
            ->shouldReceive('update')
            ->andReturn($this->mockEntityUpdated);

        $this->mockDtoInput = Mockery::mock(
            UpdateCategoryInputDto::class,
            [
                $this->uuid,
                'Category updated',
                'description updated',

            ]
        );

        $useCase = new UpdateCategoryUseCase($this->mockRepo);
        $responseDto = $useCase->execute($this->mockDtoInput);
        $this->assertInstanceOf(CategoryOutputDto::class, $responseDto);
        $this->assertEquals($this->mockEntity->id, $responseDto->id);
        $this->assertNotEquals($this->mockEntity->name, $responseDto->name);
        $this->assertNotEquals($this->mockEntity->description, $responseDto->description);
        $this->assertEquals($this->mockEntity->isActive, $responseDto->is_active);
        $this->assertEquals($this->mockEntity->createdAt(), $responseDto->created_at);

        /**
         * Spies
         */
        $this->spyRepo
            ->shouldReceive('findById')
            ->andReturn($this->mockEntity);

        $this->spyRepo
            ->shouldReceive('update')
            ->andReturn($this->mockEntityUpdated);

        $useCase = new UpdateCategoryUseCase($this->spyRepo);
        $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('findById');
        $this->spyRepo->shouldHaveReceived('update');
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
