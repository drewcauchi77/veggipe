<?php

namespace App\Http\Filters\V1;

class RecipeFilter extends QueryFilter
{
    protected int $defaultPerPage = 6;
    protected int $maxPerPage = 50;
    protected int $perPage;

    /**
     * @param string $value
     * @return void
     */
    public function itemsPerPage(string $value): void
    {
        $perPage = (int) $value;

        if ($perPage > 0 && $perPage <= $this->maxPerPage) {
            $this->perPage = $perPage;
        } else {
            $this->perPage = $this->defaultPerPage;
        }
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage ?? $this->defaultPerPage;
    }
}
