<?php

namespace Tests\Domain\Entity;

use Core\Domain\Entity\Category;
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
        $this->assertEquals(true, $category->isActive);
    }
}