<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\RecipeFilter;
use App\Http\Requests\Api\V1\Recipe\GetRecipesRequest;
use App\Http\Requests\Api\V1\Recipe\StoreRecipeRequest;
use App\Http\Resources\V1\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecipeController extends ApiController
{
    /**
     * @param GetRecipesRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetRecipesRequest $request): AnonymousResourceCollection
    {
        $filters = new RecipeFilter($request);

        return RecipeResource::collection(
            Recipe::filter($filters)
                ->paginate($filters->getPerPage())
                ->appends($request->query())
        );
    }

    public function store(StoreRecipeRequest $request): void
    {

    }
}
