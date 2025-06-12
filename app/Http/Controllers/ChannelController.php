<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Services\General\ChannelService;
use App\Traits\ApiResponseTrait;

class ChannelController extends Controller
{
    use ApiResponseTrait;

    protected ChannelService $channelService;

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    /**
     * إنشاء قناة جديدة للمستخدم.
     */
    public function store(StoreChannelRequest $request)
    {
        $channel = $this->channelService->createChannel($request->validated());
        return $this->successResponse($channel, 'Channel created successfully');
    }

    /**
     * عرض جميع القنوات الخاصة بالمستخدم.
     */
    public function index()
    {
        $channels = $this->channelService->getUserChannels();
        return $this->successResponse($channels, 'User channels retrieved successfully');
    }
}
