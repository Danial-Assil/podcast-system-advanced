<?php

namespace App\Services\Shared;

use Illuminate\Support\Facades\Cache;

class CodeService
{
    public function generate(string $prefix, string $identifier, ?int $minutes = null): string
    {
        $code = (string) rand(100000, 999999);

        // استخدم المدة الافتراضية من الإعدادات إذا لم يتم تمريرها
        $minutes ??= config('auth.verification_code_expiration', 10); // 10 دقائق افتراضية

        Cache::put($this->key($prefix, $identifier), $code, now()->addMinutes($minutes));

        return $code;
    }

    public function get(string $prefix, string $identifier): ?string
    {
        return Cache::get($this->key($prefix, $identifier));
    }

    public function forget(string $prefix, string $identifier): void
    {
        Cache::forget($this->key($prefix, $identifier));
    }

    protected function key(string $prefix, string $identifier): string
    {
        return "{$prefix}{$identifier}";
    }
}
