<?php

namespace App\Models;

use App\Http\Filters\V1\QueryFilter;
use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property-read string $excerpt
 * @method static Builder filter(QueryFilter $filters)
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

    /**
     * @param Builder $builder
     * @param QueryFilter $filters
     * @return Builder
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
