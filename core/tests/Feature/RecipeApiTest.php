<?php

use App\Models\Recipe;

describe('GET /api/v1/recipes', function () {
    it('returns empty collection when no recipes exist', function () {
        $response = $this->getJson('/api/v1/recipes');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'per_page',
                    'to',
                    'total'
                ]
            ])
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'current_page' => 1,
                    'per_page' => 6
                ]
            ]);
    });

    it('returns paginated recipes with correct structure', function () {
        Recipe::factory(10)->create();

        $response = $this->getJson('/api/v1/recipes');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'excerpt',
                            'image'
                        ]
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'per_page',
                    'to',
                    'total'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'total' => 10,
                    'current_page' => 1,
                    'per_page' => 6
                ]
            ]);

        expect($response->json('data'))->toHaveCount(6)
            ->and($response->json('data.0.type'))->toBe('recipe');
    });

    it('returns recipes with correct resource format', function () {
        $recipe = Recipe::factory()->create([
            'name' => 'Chocolate Chip Cookies',
            'description' => 'Delicious homemade chocolate chip cookies that are crispy on the outside and chewy on the inside.',
            'image' => 'https://example.com/cookie.jpg'
        ]);

        $response = $this->getJson('/api/v1/recipes');

        $response->assertOk();

        $recipeData = $response->json('data.0');

        expect($recipeData)->toMatchArray([
            'type' => 'recipe',
            'id' => $recipe->id,
            'attributes' => [
                'name' => 'Chocolate Chip Cookies',
                'excerpt' => 'Delicious homemade chocolate chip cookies that are crispy on the outside and chewy on the inside.',
                'image' => 'https://example.com/cookie.jpg'
            ]
        ]);
    });

    it('truncates description to 97 characters in excerpt', function () {
        $longDescription = str_repeat('a', 150);
        Recipe::factory()->create([
            'description' => $longDescription
        ]);

        $response = $this->getJson('/api/v1/recipes');

        $response->assertOk();

        $excerpt = $response->json('data.0.attributes.excerpt');
        expect(strlen($excerpt))->toBeLessThanOrEqual(100)
            ->and($excerpt)->toEndWith('...'); // 97 chars + "..."
    });

    it('handles pagination correctly with multiple pages', function () {
        Recipe::factory(15)->create();

        // First page
        $response = $this->getJson('/api/v1/recipes?page=1');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'total' => 15,
                    'per_page' => 6,
                    'last_page' => 3
                ]
            ]);

        expect($response->json('data'))->toHaveCount(6);

        // Second page
        $response = $this->getJson('/api/v1/recipes?page=2');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 2,
                    'total' => 15,
                    'per_page' => 6,
                    'last_page' => 3
                ]
            ]);

        expect($response->json('data'))->toHaveCount(6);

        // Third page
        $response = $this->getJson('/api/v1/recipes?page=3');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 3,
                    'total' => 15,
                    'per_page' => 6,
                    'last_page' => 3
                ]
            ]);

        expect($response->json('data'))->toHaveCount(3);
    });
});

describe('GET /api/v1/recipes with itemsperpage parameter', function () {
    beforeEach(function () {
        Recipe::factory(20)->create();
    });

    it('respects custom itemsperpage parameter', function () {
        $response = $this->getJson('/api/v1/recipes?itemsperpage=10');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 10,
                    'total' => 20,
                    'last_page' => 2
                ]
            ]);

        expect($response->json('data'))->toHaveCount(10);
    });

    it('uses default when itemsperpage is not provided', function () {
        $response = $this->getJson('/api/v1/recipes');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 6 // default from RecipeFilter
                ]
            ]);

        expect($response->json('data'))->toHaveCount(6);
    });

    it('enforces maximum items per page limit', function () {
        $response = $this->getJson('/api/v1/recipes?itemsperpage=100');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 6 // falls back to default when exceeding max
                ]
            ]);
    });

    it('handles invalid itemsperpage values', function (string $invalidValue) {
        $response = $this->getJson("/api/v1/recipes?itemsperpage={$invalidValue}");

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 6 // falls back to default
                ]
            ]);
    })->with([
        'negative' => '-5',
        'zero' => '0',
        'string' => 'abc',
        'empty' => ''
    ]);

    it('handles float itemsperpage values', function () {
        $response = $this->getJson("/api/v1/recipes?itemsperpage=5.5");

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 5
                ]
            ]);
    });

    it('accepts valid itemsperpage values within limits', function (int $validValue) {
        $response = $this->getJson("/api/v1/recipes?itemsperpage={$validValue}");

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => $validValue
                ]
            ]);

        expect($response->json('data'))->toHaveCount(min($validValue, 20)); // min with total recipes
    })->with([
        'small' => 1,
        'medium' => 15,
        'at-limit' => 50
    ]);

    it('maintains pagination with custom itemsperpage', function () {
        // Page 1 with 8 items per page
        $response = $this->getJson('/api/v1/recipes?itemsperpage=8&page=1');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 8,
                    'total' => 20,
                    'last_page' => 3
                ]
            ]);

        expect($response->json('data'))->toHaveCount(8);

        // Page 3 with 8 items per page (should have 4 items)
        $response = $this->getJson('/api/v1/recipes?itemsperpage=8&page=3');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'current_page' => 3,
                    'per_page' => 8,
                    'total' => 20,
                    'last_page' => 3
                ]
            ]);

        expect($response->json('data'))->toHaveCount(4);
    });

    it('handles edge case with itemsperpage larger than total records', function () {
        $response = $this->getJson('/api/v1/recipes?itemsperpage=25');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 25,
                    'total' => 20,
                    'current_page' => 1,
                    'last_page' => 1
                ]
            ]);

        expect($response->json('data'))->toHaveCount(20);
    });
});

describe('Recipe API error handling', function () {
    it('handles non-existent pages gracefully', function () {
        Recipe::factory(5)->create();

        $response = $this->getJson('/api/v1/recipes?page=999');

        $response->assertOk()
            ->assertJson([
                'data' => [],
                'meta' => [
                    'current_page' => 999,
                    'total' => 5
                ]
            ]);
    });

    it('handles mixed valid and invalid query parameters', function () {
        Recipe::factory(10)->create();

        $response = $this->getJson('/api/v1/recipes?itemsperpage=5&invalidparam=test&page=2');

        $response->assertOk()
            ->assertJson([
                'meta' => [
                    'per_page' => 5,
                    'current_page' => 2,
                    'total' => 10
                ]
            ]);

        expect($response->json('data'))->toHaveCount(5);
    });
});
