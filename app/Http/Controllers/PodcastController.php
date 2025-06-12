<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePodcastRequest;
use App\Services\General\PodcastService;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\RandomPodcastRequest;

class PodcastController extends Controller
{
    use ApiResponseTrait;

    protected PodcastService $podcastService;

    public function __construct(PodcastService $podcastService)
    {
        $this->podcastService = $podcastService;
    }

    public function store(StorePodcastRequest $request)
    {
        $podcast = $this->podcastService->store(array_merge(
            $request->validated(),
            [
                'audio_file' => $request->file('audio_file'),
                'cover_image' => $request->file('cover_image'),  // إضافة صورة الغلاف هنا
            ]
        ));

        return $this->successResponse($podcast, 'Podcast published successfully');
    }

    public function index(int $channelId)
    {
        $podcasts = $this->podcastService->getByChannel($channelId);
        return $this->successResponse($podcasts, 'Podcasts retrieved successfully');
    }



    public function show($podcastId)
    {
        $podcast = $this->podcastService->getByIdWithComments((int) $podcastId);
        return $this->successResponse($podcast, 'Podcast with comments retrieved successfully');
    }



    public function toggleLike(int $podcastId)
    {
        $liked = $this->podcastService->toggleLike($podcastId);

        return $this->successResponse(['liked' => $liked], $liked ? 'Liked the podcast' : 'Unliked the podcast');
    }

    public function randomList(RandomPodcastRequest $request)
    {
        $perPage = $request->validated()['per_page'] ?? 10;
        $podcasts = $this->podcastService->getRandomPodcasts($perPage);
        return $this->successResponse($podcasts, 'Random podcasts retrieved successfully');
    }

}
