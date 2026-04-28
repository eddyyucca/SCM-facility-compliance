<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class HrAttachmentController extends Controller
{
    public function show(string $filename)
    {
        $filename = basename($filename);
        $path = 'hr-requests/' . $filename;

        abort_unless(Storage::disk('public')->exists($path), 404);

        $disk = Storage::disk('public');
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return response($disk->get($path), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
