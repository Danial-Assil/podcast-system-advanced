<?php
namespace Database\Factories;

use App\Models\Comment;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'podcast_id' => Podcast::factory(),
            'parent_id' => null,
            'content' => $this->faker->sentence,
        ];
    }
}
