<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\RecipeFilter;
use App\Http\Requests\Api\V1\StoreRecipeRequest;
use App\Http\Resources\V1\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RecipeController extends ApiController
{
    /**
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $filters = new RecipeFilter($request);

        return RecipeResource::collection(
            Recipe::filter($filters)
                ->paginate($filters->getPerPage())
                ->appends($request->query())
        );
    }

    public function store(StoreRecipeRequest $request)
    {

    }
}
