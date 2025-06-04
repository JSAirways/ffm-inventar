<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ThumbnailGenerator
{
    public function createThumbnail(string $path): string
    {
        $storage = Storage::disk('public');
        $thumbPath = $this->getThumbnailPath($path);

        if ($storage->exists($thumbPath)) {
            return $thumbPath;
        }

        $image = Image::make($storage->path($path));
        $image->fit(300, 300); // Square thumb, adjust as needed
        $storage->put($thumbPath, (string) $image->encode(null, 80));

        return $thumbPath;
    }

    public function deleteThumbnail(string $path): void
    {
        $thumbPath = $this->getThumbnailPath($path);
        if (Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }
    }

    public function getThumbnailPath(string $originalPath): string
    {
        return str_replace('items/gallery/', 'items/gallery/thumbnails/', pathinfo($originalPath, PATHINFO_DIRNAME) . '/' . pathinfo($originalPath, PATHINFO_FILENAME) . '_thumb.' . pathinfo($originalPath, PATHINFO_EXTENSION));
    }
}
