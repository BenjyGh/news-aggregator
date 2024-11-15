<?php

use App\Models\Author;

test('user can get authors list and result is in expected format', function () {
    Author::factory(5)->create();

    $response = $this->getJson(route('author:index'));

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
    Author::factory(5)->create();

    $response = $this->getJson(route('author:index'));

    assertResponseIsPaginated($response);
});
