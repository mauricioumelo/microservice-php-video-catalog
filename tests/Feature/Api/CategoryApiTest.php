<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    protected $endpoint = 'api/categories';
    public function test_list_empty_categories(): void
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonFragment(['data' => []]);
        $this->assertEquals(1, $response['meta']['current_page']);
        $this->assertIsArray($response['meta']['links']);
        $this->assertIsArray($response['meta']['pagination_info']);
        $this->assertEquals(15, $response['meta']['per_page']);
        $this->assertEquals(0, $response['meta']['total_items']);
        $this->assertEquals(1, $response['meta']['total_pages']);
    }

    public function test_list_all_categories(): void
    {
        $categories = Category::factory(40)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');

        $this->assertEquals(1, $response['meta']['current_page']);
        $this->assertEquals(15, $response['meta']['per_page']);
        $this->assertEquals(40, $response['meta']['total_items']);
        $this->assertEquals(3, $response['meta']['total_pages']);
    }

    public function test_list_paginate_categories(): void
    {
        $categories = Category::factory(40)->create();

        $response = $this->getJson("{$this->endpoint}?page=2");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');

        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(15, $response['meta']['per_page']);
        $this->assertEquals(40, $response['meta']['total_items']);
        $this->assertEquals(3, $response['meta']['total_pages']);
    }

    public function test_category_not_found(): void
    {
        $response = $this->getJson("{$this->endpoint}/fakeValue");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Category Not Found', $response['message']);
    }

    public function test_list_category(): void
    {
        $category = Category::factory()->create()->first();
        $response = $this->getJson("{$this->endpoint}/{$category->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['data' => [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->is_active,
            'created_at' => Carbon::make($category->created_at)->format('Y-m-d H:i:s'),
        ]]);
    }

    public function test_validations_store(): void
    {
        $data = [];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function test_store(): void
    {
        $data = [
            'name' => 'name of first category'
        ];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ],
        ]);
        $this->assertEquals($response['data']['name'], $data['name']);

        $data = [
            'name' => 'name of second category',
            'description' => 'description of second category',
            'is_active' => false
        ];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ],
        ]);
        $this->assertEquals($response['data']['name'], $data['name']);
        $this->assertEquals($response['data']['description'], $data['description']);
        $this->assertEquals($response['data']['is_active'], $data['is_active']);
    }
    public function test_update_category_not_found(): void
    {
        $response = $this->putJson("{$this->endpoint}/fakeValue", ['name' => 'name category Update']);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Category Not Found', $response['message']);
    }

    public function test_validations_update(): void
    {
        $category = Category::factory()->create()->first();

        $data = [];
        $response = $this->putJson("$this->endpoint/$category->id", $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function test_update(): void
    {
        $categories = Category::factory(3)->create();

        $data = [
            'name' => 'name update category'
        ];

        $response = $this->putJson("{$this->endpoint}/{$categories[0]->id}", $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ],
        ]);
        $this->assertEquals($response['data']['name'], $data['name']);
        $this->assertDatabaseHas('categories', ['description' => $categories[0]->description, ...$data]);


        $data = [
            'name' => 'name of second category update',
            'description' => 'description of second category update',
            'is_active' => false
        ];

        $response = $this->putJson("{$this->endpoint}/{$categories[1]->id}", $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ],
        ]);

        $this->assertEquals($response['data']['name'], $data['name']);
        $this->assertEquals($response['data']['description'], $data['description']);
        $this->assertEquals($response['data']['is_active'], $data['is_active']);
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_destroy_category_not_found(): void
    {
        $response = $this->deleteJson("{$this->endpoint}/fakeValue");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals('Category Not Found', $response['message']);
    }

    public function test_destroy(): void
    {
        $category = Category::factory()->create()->first();

        $response = $this->deleteJson("{$this->endpoint}/$category->id");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('categories', ['id' => $category->id,]);
    }
}
