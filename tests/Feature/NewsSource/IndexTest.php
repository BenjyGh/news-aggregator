<?php

use App\Models\NewsSource;

test('user can get news sources list and result is in expected format', function () {
    NewsSource::factory(5)->create();

    $response = $this->getJson(route('news-source:index'));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'url'
                ],
            ]
        ]);
});

test('response is paginated', function () {
    NewsSource::factory(10)->create();

    $response = $this->getJson(route('news-source:index'));

    assertResponseIsPaginated($response);
});
