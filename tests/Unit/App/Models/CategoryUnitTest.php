<?php

namespace Tests\Unit\App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    protected function traits(): array
    {
        return [
            \Illuminate\Database\Eloquent\Factories\HasFactory::class,
            \Illuminate\Database\Eloquent\SoftDeletes::class,
        ];
    }

    protected function casts(): array
    {
        return  [
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    protected function fillable(): array
    {
        return [
            'id',
            'name',
            'description',
            'is_active',
        ];
    }
}
