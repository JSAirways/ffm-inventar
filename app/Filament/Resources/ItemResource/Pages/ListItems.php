<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\Location;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    public function getTabs(): array
    {
        $tabs = [];

        $tabs['all'] = ListRecords\Tab::make('All');

        foreach (Location::all() as $location) {
            $slug = 'location-' . $location->slug;

            $tabs[$slug] = ListRecords\Tab::make($location->name)
                ->modifyQueryUsing(function (Builder $query) use ($location) {
                    $query->where('location_id', $location->id);
                });
        }

        return $tabs;
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
