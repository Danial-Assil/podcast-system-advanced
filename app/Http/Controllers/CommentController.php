<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Services\General\CommentService;
use App\Traits\ApiResponseTrait;

class CommentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected CommentService $commentService) {}

    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->store($request->validated());
        return $this->successResponse($comment, 'Comment posted successfully');
    }

    public function index(int $podcastId)
    {
        $comments = $this->commentService->getByPodcast($podcastId);
        return $this->successResponse($comments, 'Comments retrieved successfully');
    }
}
