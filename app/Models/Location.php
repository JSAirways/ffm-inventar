<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    protected static function booted()
    {
        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = \Str::slug($location->name);
            }
        });

        static::updating(function ($location) {
            $location->slug = Str::slug($location->name);
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
