<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ComplaintPhotoController extends Controller
{
    public function show(string $filename)
    {
        $filename = basename($filename);
        $path = 'complaints/' . $filename;

        abort_unless(Storage::disk('public')->exists($path), 404);

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $contentType = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
