<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ItemImage extends Model
{
    use HasFactory;
    protected $fillable = ['path'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Optional: access to full URL
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path); // ← Note: `path`, not `file`
    }


}
