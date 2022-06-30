<?php
namespace Database\Factories;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'status' => Blog::OPEN,
            'title' => $this->faker->realText(20),
            'body' => $this->faker->realText(100)
        ];
    }

    public function seeding()
    {
        return $this->state(function () {
            return  [
                'status' => $this->faker->biasedNumberBetween(0, 1, ['\Faker\Provider\Biased', 'linearHigh'])
            ];
        });
    }

    public function close()
    {
        return $this->state(function () {
            return  [
                'status' => Blog::CLOSED
            ];
        });
    }

    public function withCommentsData(array $comments)
    {
        return $this->afterCreating(function (Blog $blog) use ($comments) {
            foreach ($comments as $comment) {
                Comment::factory()->create(array_merge([
                    'blog_id' => $blog->id,
                ], $comment));
            }
        });
    }
}
