<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsSource>
 */
class NewsSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['technology', 'health', 'business', 'education', 'entertainment',
            'travel', 'food', 'lifestyle', 'fashion', 'politic'];

        return [
            'name' => $this->faker->unique()->company(),
            'url' => $this->faker->unique()->url(),
            'category_name' => $this->faker->randomElement($categories)
        ];
    }
}
