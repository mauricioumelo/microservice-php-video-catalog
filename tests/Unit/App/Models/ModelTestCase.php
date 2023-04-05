<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;

    abstract protected function traits(): array;

    abstract protected function casts(): array;

    abstract protected function fillable(): array;

    public function test_if_use_traits(): void
    {
        $traits = array_keys(class_uses($this->model()));
        $this->assertEquals($this->traits(), $traits);
    }

    public function test_if_auto_increment_is_false(): void
    {
        $model = $this->model();

        $this->assertFalse($model->incrementing);
    }

    public function test_has_casts(): void
    {
        $model = $this->model();

        $expectedCasts = $this->casts();

        $this->assertEquals($expectedCasts, $model->getCasts());
    }

    public function test_has_fillable(): void
    {
        $model = $this->model();

        $expectedFillable = $this->fillable();

        $this->assertEquals($expectedFillable, $model->getFillable());
    }
}
