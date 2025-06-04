<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::insert([
            ['name' => 'FFM', 'slug' => 'ffm'],
            ['name' => 'Zeil Center', 'slug' => 'zeil'],
            ['name' => 'Stuttgart', 'slug' => 'stuttgart'],
        ]);
    }
}

