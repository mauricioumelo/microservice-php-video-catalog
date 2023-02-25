<?php

namespace Tests\Domain\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\DTO\Category\Delete\DeleteCategoryOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteCategoryUseCaseUnitTest extends TestCase
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
        $this->mockEntity = Mockery::mock(Category::class, [
            'TEST DELETE CATEGORY',
            $this->uuid
        ]);

        $this->mockDtoInput = Mockery::mock(CategoryInputDto::class, [
            $this->uuid
        ]);

        $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
    }


    /**
     * @dataProvider providerDeleteCategory
     */
    public function test_delete_category($expected): void
    {
        echo "<pre>" . var_dump($expected);
        $this->mockEntity->shouldReceive('id')->andReturn($this->uuid);

        $this->mockRepo
            ->shouldReceive('findById')
            ->andReturn($this->mockEntity);

        $this->mockRepo
            ->shouldReceive('delete')
            ->andReturn($expected);

        $this->mockDtoInput = Mockery::mock(
            CategoryInputDto::class,
            [
                $this->uuid
            ]
        );

        $useCase = new DeleteCategoryUseCase($this->mockRepo);
        $responseDto = $useCase->execute($this->mockDtoInput);
        $this->assertInstanceOf(DeleteCategoryOutputDto::class, $responseDto);
        $this->assertSame($expected, $responseDto->success);

        /**
         * Spies
         */
        $this->spyRepo
            ->shouldReceive('findById')
            ->once() 
            ->andReturn($this->mockEntity);

        $this->spyRepo
            ->shouldReceive('delete')
            ->once() 
            ->andReturn($expected);


        $useCase = new DeleteCategoryUseCase($this->spyRepo);
        $useCase->execute($this->mockDtoInput);
        $this->spyRepo->shouldHaveReceived('findById');
        $this->spyRepo->shouldHaveReceived('delete');
    }

    public static function providerDeleteCategory(): array
    {
        return [
            'test_delete_category_false' => [false],
            'test_delete_category_true' => [true],
        ];
    }
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
