<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(5),
            'url' => $this->faker->url(),
            'image_url' => $this->faker->imageUrl(),
            'news_source_id' => NewsSource::factory(),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'published_at' => $this->faker->dateTimeBetween('-24 month', 'now'),
        ];
    }
}
