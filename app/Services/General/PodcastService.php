<?php

namespace App\Services\General;

use App\Models\Podcast;
use App\Models\PodcastLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class PodcastService
{
    /**
     * إنشاء بودكاست جديد مع حفظ الملف الصوتي وصورة الغلاف (اختياري).
     *
     * @param array $data
     * @return Podcast
     * @throws InvalidArgumentException
     */
    public function store(array $data): Podcast
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // التأكد من أن الملف الصوتي موجود وصالح
        if (!isset($data['audio_file']) || !($data['audio_file'] instanceof UploadedFile)) {
            throw new InvalidArgumentException('Audio file is required and must be valid.');
        }

        // التأكد من أن القناة تابعة للمستخدم
        $channel = $user->channels()->findOrFail($data['channel_id']);

        // حفظ الملف الصوتي
        $audioPath = $data['audio_file']->store('podcasts/audio', 'public');

        // حفظ صورة الغلاف إن وجدت
        $coverImagePath = isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile
            ? $data['cover_image']->store('podcasts/covers', 'public')
            : null;

        // إنشاء البودكاست
        $podcast = $channel->podcasts()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'audio_path' => $audioPath,
            'cover_image' => $coverImagePath,
        ]);

        // ربط التصنيفات إذا وُجدت
        if (!empty($data['category_ids']) && is_array($data['category_ids'])) {
            $podcast->categories()->sync($data['category_ids']);
        }

        return $podcast;
    }

    /**
     * جلب جميع البودكاستات الخاصة بقناة معينة، مرتبة من الأحدث.
     *
     * @param int $channelId
     * @return Collection
     */
    public function getByChannel(int $channelId): Collection
    {
        return Podcast::where('channel_id', $channelId)
            ->latest()
            ->get();
    }



    public function getByIdWithComments(int $podcastId): Podcast
    {
        return Podcast::with(['comments.user', 'comments.replies.user'])->findOrFail($podcastId);
    }



    public function toggleLike(int $podcastId): bool
    {
        $user = Auth::user();

        $like = PodcastLike::where('user_id', $user->id)
            ->where('podcast_id', $podcastId)
            ->first();

        if ($like) {
            $like->delete();
            return false; // إلغاء الإعجاب
        } else {
            PodcastLike::create([
                'user_id' => $user->id,
                'podcast_id' => $podcastId,
            ]);
            return true; // تم الإعجاب
        }
    }


    public function getRandomPodcasts(int $perPage = 10): LengthAwarePaginator
    {
        return Podcast::inRandomOrder()->paginate($perPage);
    }


}
