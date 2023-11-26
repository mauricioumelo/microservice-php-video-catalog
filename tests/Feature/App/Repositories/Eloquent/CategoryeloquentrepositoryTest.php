<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use stdClass;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function test_insert(): void
    {
        $repository = $this->repository;

        $entity = new EntityCategory(
            'category test insert'
        );

        $response = $repository->create($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', ['name' => $response->name]);
        $this->assertEquals('category test insert', $response->name);
    }

    public function test_find_by_id(): void
    {
        $repository = $this->repository;

        $category = Model::factory(10)->create()->first();

        $response = $repository->findById($category->id);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($response->id, $category->id);
    }

    public function test_find_by_id_fail(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Category not found in data base');
        $this->expectExceptionCode(404);

        $repository = $this->repository;

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);

        $category = Model::factory(2)->create()->first();

        $response = $repository->findById('23');
    }

    public function test_get_all_users(): void
    {
        $repository = $this->repository;

        $categories = Model::factory(10)->create();

        $response = $repository->get();

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertIsArray($response);
        $this->assertInstanceOf(EntityCategory::class, $response[0]);
        $this->assertCount(10, $response);
    }

    public function test_get_all_users_empty(): void
    {
        $repository = $this->repository;

        $response = $repository->get();

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_get_all_users_with_filter(): void
    {
        $repository = $this->repository;

        $categories = Model::factory(10)->create();

        $response = $repository->get(filters: $categories->first()->name);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertIsArray($response);
        $this->assertInstanceOf(EntityCategory::class, $response[0]);
        $this->assertNotNull($response);
        $this->assertEquals($categories->first()->name, $response[0]->name);
    }

    public function test_get_all_users_with_order(): void
    {
        $repository = $this->repository;

        $categories = Model::factory(10)->create()->sortBy('id');

        $response = $repository->get(order: 'ASC');

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertIsArray($response);
        $this->assertInstanceOf(EntityCategory::class, $response[0]);
        $this->assertCount(10, $response);
        $this->assertEquals($categories->first()->name, $response[0]->name);
    }

    public function test_pagination_categories(): void
    {
        $repository = $this->repository;

        $categories = Model::factory(30)->create();

        $response = $repository->paginate();
        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(PaginateInterface::class, $response);
        $this->assertCount(15, $response->items());
        $this->assertInstanceOf(stdClass::class, $response->items()[0]);
    }

    public function test_pagination_categories_empty(): void
    {
        $repository = $this->repository;

        $response = $repository->paginate();

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(PaginateInterface::class, $response);
        $this->assertEmpty($response->items());
        $this->assertEquals(0, $response->totalItems());
    }

    public function test_update_category(): void
    {
        $repository = $this->repository;

        $category = Model::factory(4)->create()->first();


        $entity = $repository->findById($category->id);

        $entity->update([
            'name' => 'category updated by repository',
            'description' => 'description of test category updated by repository'
        ]);

        $response = $repository->update($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertNotEquals($category->name, $response->name);
        $this->assertNotEquals($category->description, $response->description);
    }

    public function test_update_category_not_found(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Category not found in data base');
        $this->expectExceptionCode(404);

        $repository = $this->repository;

        $category = Model::factory(4)->create()->first();

        $entity = $repository->findById('2325126');

        $entity->update([
            'name' => 'category updated by repository',
            'description' => 'description of test category updated by repository'
        ]);

        $response = $repository->update($entity);
    }

    public function test_delete_category(): void
    {
        $repository = $this->repository;

        $category = Model::factory(1)->create()->first();

        $response = $repository->delete($category->id);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $repository);
        $this->assertTrue($response);
        $this->assertDatabaseMissing('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_delete_category_not_found(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Category not found');
        $this->expectExceptionCode(404);

        $repository = $this->repository;

        $category = Model::factory(1)->create()->first();

        $response = $repository->delete('fake_id');
    }
}
