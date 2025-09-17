<?php

namespace App\Http\Requests\Api\V1\Recipe;

use App\Models\User;
use App\Permissions\V1\RecipeAbilities;
use Illuminate\Foundation\Http\FormRequest;

class GetRecipesRequest extends FormRequest
{
    /**
     * @param User $user
     * @return bool
     */
    public function authorize(User $user): bool
    {
        return $user->tokenCan(RecipeAbilities::GetRecipes);
    }
}
