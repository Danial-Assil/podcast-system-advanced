<?php

namespace App\Services\General;

use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ChannelService
{
    /**
     * إنشاء قناة جديدة للمستخدم الحالي.
     *
     * @param array $data
     * @return Channel
     */
    public function createChannel(array $data): Channel
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->channels()->create($data);
    }

    /**
     * جلب كل قنوات المستخدم الحالي.
     *
     * @return Collection|Channel[]
     */
    public function getUserChannels(): Collection
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->channels()->latest()->get();
    }
}
