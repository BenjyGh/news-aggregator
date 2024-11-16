<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

/*
 * Here we have to use DatabaseMigrations instead of RefreshDatabase
 * which is significantly slower because for each test it needs to
 * migrate the whole database
 *
 * problem with RefreshDatabase is that we need to test our fulltext search,
 * and fulltext search doesn't work inside transactions before commit.
 *
 * https://dev.mysql.com/doc/refman/8.4/en/innodb-fulltext-index.html#innodb-fulltext-index-transaction
 */
pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\DatabaseMigrations::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function assertResponseIsPaginated(Illuminate\Testing\TestResponse $response): void
{
    $response
        ->assertOk()
        ->assertJsonStructure([
            'meta' => [
                'current_page',
                'from',
                'path',
                'per_page',
                'to',
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
        ]);
}
