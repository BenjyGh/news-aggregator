<?php

use App\Models\Category;

test('user can get categories list and result is in expected format', function () {
    Category::factory(5)->create();

    $response = $this->getJson(route('category:index'));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ]
        ]);
});

test('response is paginated', function () {
    Category::factory(10)->create();

    $response = $this->getJson(route('category:index'));

    assertResponseIsPaginated($response);
});
