<?php

namespace App\Permissions\V1;

final class RecipeAbilities
{
    public const GetRecipes = 'recipe:index';

    public static function GetRecipeAbilities(): array
    {
        return [
            self::GetRecipes,
        ];
    }
}
