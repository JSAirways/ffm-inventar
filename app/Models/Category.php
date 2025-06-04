<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id'];

    protected static function booted()
    {
        static::creating(function ($room) {
            if (empty($room->slug)) {
                $room->slug = \Str::slug($room->name);
            }
        });

        static::updating(function ($room) {
            $room->slug = Str::slug($room->name);
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
