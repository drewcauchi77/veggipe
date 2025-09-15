<?php

namespace App\Models;

use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property-read string $excerpt
 */
class Recipe extends Model
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $appends = [
        'excerpt',
    ];

    /**
     * @return string
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit($this->description, 97);
    }
}
