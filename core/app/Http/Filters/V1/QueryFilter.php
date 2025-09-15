<?php

namespace App\Http\Filters\V1;

use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
