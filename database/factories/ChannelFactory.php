<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // ينشئ مستخدم تلقائيًا
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->sentence,
        ];
    }
}
