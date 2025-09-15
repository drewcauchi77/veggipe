<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Request $request;
    protected Builder $builder;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value)
        {
            if (method_exists($this, $key))
            {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->request->all();
    }
}
