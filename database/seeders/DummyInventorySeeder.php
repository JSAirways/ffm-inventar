<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Location;
use App\Models\Room;
use Illuminate\Support\Str;

class DummyInventorySeeder extends Seeder
{
    public function run(): void
    {
        $location = Location::firstOrCreate(['name' => 'Main Office']);
        $room = Room::firstOrCreate([
            'name' => 'Storage Room',
            'location_id' => $location->id,
        ]);

        Item::factory()->create([
            'item_id' => 'DZG-001',
            'description' => 'Projector',
            'amount' => 1,
            'status' => fake()->randomElement(['in_stock', 'in_progress', 'reserved']),
            'location_id' => $location->id,
            'room_id' => $room->id,
        ]);

        Item::factory()->count(5)->create([
            'item_id' => fn () => 'DZG-' . strtoupper(Str::random(5)),
            'location_id' => $location->id,
            'room_id' => $room->id,
            'status' => fake()->randomElement(['in_stock', 'in_progress', 'reserved']),
        ]);
    }
}