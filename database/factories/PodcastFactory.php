<?php
namespace Database\Factories;

use App\Models\Podcast;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PodcastFactory extends Factory
{
    protected $model = Podcast::class;

    public function definition()
    {
        return [
            'channel_id' => Channel::factory(), // سيخلق قناة تلقائياً إذا لم توجد
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'audio_path' => 'podcasts/audio/sample.mp3', // يمكن استبداله بنموذج تخيلي
            'cover_image' => null,
            'published_at' => now(),
        ];
    }
}
