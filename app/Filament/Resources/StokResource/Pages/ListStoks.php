<?php

namespace App\Filament\Resources\StokResource\Pages;

use App\Filament\Resources\StokResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoks extends ListRecords
{
    protected static string $resource = StokResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
