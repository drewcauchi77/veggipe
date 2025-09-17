<?php

namespace App\Http\Requests\Api\V1\Recipe;

use App\Permissions\V1\RecipeAbilities;
use Illuminate\Foundation\Http\FormRequest;

class GetRecipesRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()?->tokenCan(RecipeAbilities::GetRecipes) ?? false;
    }
}
