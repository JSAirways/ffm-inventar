<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageProcessor
{
    public function handle(UploadedFile $file): string
    {
        $image = Image::make($file->getRealPath());

        // Resize only if larger than 1920x1080
        if ($image->width() > 1920 || $image->height() > 1080) {
            $image->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $filename = 'items/gallery/' . uniqid() . '.' . $file->getClientOriginalExtension();

        Storage::disk('public')->put($filename, (string) $image->encode(null, 80));

        return $filename;
    }
}

