<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;

class PodcastSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 10 بودكاستات فقط (بدون تعليقات)
        Podcast::factory(10)->create();
    }
}
