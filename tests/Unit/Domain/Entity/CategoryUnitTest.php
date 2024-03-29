<?php

namespace Tests\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CategoryUnitTest extends TestCase
{
    public function test_attributes()
    {
        $category = new Category(
            name: 'New Category',
            description: 'this is description of New Category',
            isActive: true
        );

        $this->assertNotEmpty($category->id());
        $this->assertIsString($category->id());
        $this->assertNotEmpty($category->createdAt());
        $this->assertIsString($category->createdAt());
        $this->assertEquals('New Category', $category->name);
        $this->assertEquals('this is description of New Category', $category->description);
        $this->assertTrue($category->isActive);
    }

    public function test_activate()
    {
        $category = new Category(
            name: 'New Category',
            isActive: false
        );

        $this->assertFalse($category->isActive);
        $category->activate();
        $this->assertTrue($category->isActive);
    }

    public function test_disable()
    {
        $category = new Category(
            name: 'New Category',
        );

        $this->assertTrue($category->isActive);
        $category->disable();
        $this->assertFalse($category->isActive);
    }

    public function test_update()
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $category = new Category(
            id: $uuid,
            name: 'New Category',
            description: 'this is description of New Category',
            isActive: true
        );

        $categoryOld = $category;

        $category->update([
            'name' => 'new_name',
            'description' => 'this is description of new_name',
        ]);

        $this->assertEquals($categoryOld->id(), $category->id());
        $this->assertEquals($categoryOld->createdAt(), $category->createdAt());
        $this->assertNotEquals('New Category', $category->name);
        $this->assertNotEquals('this is description of New Category', $category->description);
    }

    public function test_exception_name()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('field name is required');

        new Category(
            name: '',
        );

        $this->assertTrue(false);
    }

    public function test_exception_description_max_length()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('field description has exceeded the character limit');

        new Category(
            name: 'nome',
            description:random_bytes(256)
        );

        $this->assertTrue(false);
    }

    public function test_exception_description_min_length()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('field description has less than 10 characters');

        new Category(
            name: 'nome',
            description:random_bytes(8)
        );

        $this->assertTrue(false);
    }
}
