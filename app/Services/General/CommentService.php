<?php

namespace App\Services\General;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{
    /**
     * Store a new comment or reply.
     *
     * @param array $data ['podcast_id' => int, 'content' => string, 'parent_id' => int|null]
     * @return Comment
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function store(array $data): Comment
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure the parent comment exists if parent_id is provided
        if (!empty($data['parent_id'])) {
            Comment::where('id', $data['parent_id'])->firstOrFail();
        }

        return $user->comments()->create([
            'podcast_id' => $data['podcast_id'],
            'content'    => $data['content'],
            'parent_id'  => $data['parent_id'] ?? null,
        ]);
    }

    /**
     * Retrieve all comments for a given podcast, ordered from newest to oldest.
     *
     * @param int $podcastId
     * @return Collection|Comment[]
     */
    public function getByPodcast(int $podcastId): Collection
    {
        return Comment::with(['user', 'replies.user'])
            ->where('podcast_id', $podcastId)
            ->whereNull('parent_id')
            ->latest()
            ->get();
    }
}
