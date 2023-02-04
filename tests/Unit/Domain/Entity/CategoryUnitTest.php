<?php

namespace Tests\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends TestCase
{
    public function test_attributes()
    {
        $category = new Category(
            name: 'New Category',
            description: 'this is description of New Category',
            isActive: true
        );

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
        $uuid = 'uuid.value';

        $category = new Category(
            id: $uuid,
            name: 'New Category',
            description: 'this is description of New Category',
            isActive: true
        );
        $categoryOld = $category;

        $category->update([
            'name'=> "new_name",
            'description'=> 'this is description of new_name'
        ]);

        $this->assertNotEquals('New Category', $category->name);
        $this->assertNotEquals('this is description of New Category', $category->description);
    }

    public function test_exception_name()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('field name is required');

        $category = new Category(
            name: '',
        );


        $this->assertTrue(false);
    }
}
