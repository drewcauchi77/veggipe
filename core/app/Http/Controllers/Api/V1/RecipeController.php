<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecipeController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return RecipeResource::collection(Recipe::query()->paginate(6));
    }
}
