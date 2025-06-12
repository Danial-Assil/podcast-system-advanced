<?php
//app/Http/Controllers/UserMediaController.php;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadUserMediaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class UserMediaController extends Controller
{
    public function store(UploadUserMediaRequest $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }

    $file = $request->file('file');


    $newFileName = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();


    $path = $file->storeAs('user_images', $newFileName);

    return response()->json([
        'status' => true,
        'message' => 'Image uploaded successfully',
        'file_path' => asset('storage/' . $path),
    ]);
}

    public function show()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User not authenticated'], 401);
    }


    return response()->json([
        'status' => true,
        'message' => 'User authenticated',
        'user' => $user
    ]);
}
}
