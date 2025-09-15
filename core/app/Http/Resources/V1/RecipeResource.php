<?php

namespace App\Http\Resources\V1;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Recipe $recipe */
        $recipe = $this->resource;

        return [
            'type' => 'recipe',
            'id' => $recipe->id,
            'attributes' => [
                'name' => $recipe->name,
                'description' => $recipe->description
            ]
        ];
    }
}
