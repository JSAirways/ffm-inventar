<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\ThumbnailGenerator;


class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'location_id',
        'category_id',
        'amount',
        'status',
        'notes',
        'loaned_to_location_id',
    ];

    protected $casts = [
        'status' => \App\Enums\ItemStatus::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            // Store previous first image path in memory for use after saving
            $item->oldFirstImagePath = $item->images()->orderBy('order_column')->first()?->path;
        });

        static::saved(function (self $item) {
            $thumbGen = app(\App\Services\ThumbnailGenerator::class);
            $newFirstImage = $item->images()->orderBy('order_column')->first();

            if (! $newFirstImage) return;

            // If first image has changed, delete old thumbnail
            if (isset($item->oldFirstImagePath) && $item->oldFirstImagePath !== $newFirstImage->path) {
                $thumbGen->deleteThumbnail($item->oldFirstImagePath);
            }

            $thumbGen->createThumbnail($newFirstImage->path);
        });
    }


    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $firstImage = $this->images->sortBy('order_column')->first();

        if (! $firstImage) return null;

        $thumbGen = app(\App\Services\ThumbnailGenerator::class);
        $thumbPath = $thumbGen->getThumbnailPath($firstImage->path);

        return Storage::disk('public')->url($thumbPath);
    }


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loanedToLocation()
    {
        return $this->belongsTo(Location::class, 'loaned_to_location_id');
    }

}
