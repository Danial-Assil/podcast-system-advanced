<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // نحصل على كل البودكاستات الموجودة
        $podcasts = Podcast::all();

        foreach ($podcasts as $podcast) {
            // تعليقات رئيسية
            $comments = Comment::factory(rand(1, 5))->create([
                'podcast_id' => $podcast->id,
                'parent_id' => null,
            ]);

            // ردود على كل تعليق رئيسي
            foreach ($comments as $comment) {
                Comment::factory(rand(0, 3))->create([
                    'podcast_id' => $podcast->id,
                    'parent_id' => $comment->id,
                ]);
            }
        }
    }
}
