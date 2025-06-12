<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePodcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'channel_id' => 'required|exists:channels,id',
            'audio_file' => 'required|file|mimes:mp3,wav,ogg|max:20480', // 20MB max
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ];
    }
}
